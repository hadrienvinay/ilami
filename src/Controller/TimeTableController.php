<?php
// src/Controller/TimeTableController.php
namespace App\Controller;

use App\Entity\User;
use App\Form\ConvertorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use YoutubeDl\YoutubeDl;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;

class TimeTableController extends Controller
{
    /**
     * @return Response
     * @throws \Exception
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     *
     * Call an extern api for ratp timetable
     */
    public function timetable()
    {
        $user = $this->getUser();
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
        $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
        $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
        $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
            
        //Metro 10 A-R
        $url = "https://api-ratp.pierre-grimaud.fr/v4/schedules/metros/10/boulogne-jean-jaures/A+R";
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {
            throw new \Exception("API error, not return 200");
        }
        // get Json as string
        $content = $response->getContent();
        //parse to php array
        $content = $response->toArray();


        //Bus 72 A-R
        $url2 = "https://api-ratp.pierre-grimaud.fr/v4/schedules/buses/72/route+de+la+reine+jean+jaures/A+R";
        $response = $client->request('GET', $url2);

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {
            throw new \Exception("API error, not return 200");
        }
        // get Json as string
        $content2 = $response->getContent();
        //parse to php array
        $content2 = $response->toArray();

        //Bus 52 A-R
        $url3 = "https://api-ratp.pierre-grimaud.fr/v4/schedules/buses/52/jean+jaures/A+R";
        $response = $client->request('GET', $url3);

        $statusCode = $response->getStatusCode();
        if ($statusCode != 200)
        {
            throw new \Exception("API error, not return 200");
        }
        // get Json as string
        $content3 = $response->getContent();
        //parse to php array
        $content3 = $response->toArray();

        return $this->render('my/timetable.html.twig',array(
            'metro1' => $content['result']['schedules'],
            'bus1' => $content2['result']['schedules'],
            'bus2' => $content3['result']['schedules'],
            'notificationList' => $notificationList
        ));
    }
    
    public function convertor(Request $request){
        $user = $this->getUser();
        $notifiableRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableNotification');
        $notifiableEntityRepo = $this->get('doctrine.orm.entity_manager')->getRepository('MgiletNotificationBundle:NotifiableEntity');
        $notifiable = $notifiableEntityRepo->findOneby(array("identifier" => $user));
        $notificationList = $notifiableRepo->findAllForNotifiableId($notifiable);
       
        $formBuilder = $this->get('form.factory')->createBuilder(ConvertorType::class);  
        
        $form = $formBuilder->getForm();
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $url = $form['url']->getData();
            $dl = new YoutubeDl([
                'extract-audio' => true,
                'audio-format' => 'mp3',
                'audio-quality' => 0, // best
                'output' => '%(title)s.%(ext)s',
            ]);
            // For more options go to https://github.com/rg3/youtube-dl#user-content-options

            $dl->setDownloadPath($this->getParameter('download_directory'));
            $dl->setBinPath('C:\wamp64\youtube-dl');        
            $filePath=false;
            try {
                $video = $dl->download($url);
                $filePath = $this->getParameter('download_directory').'/'.$video->getTitle().'.mp3';
                //$video->getFile(); // \SplFileInfo instance of downloaded file
            } catch (NotFoundException $e) {
                // Video not found
                $error = "Cette vidéo n'existe pas";
            } catch (PrivateVideoException $e) {
                // Video is private
                $error = "Cette vidéo est privée";

            } catch (CopyrightException $e) {
                // The YouTube account associated with this video has been terminated due to multiple third-party notifications of copyright infringement
                $error = "Copyright Error";
                
            } catch (\Exception $e) {
                // Failed to download
                $error = "Echec lors du téléchargement";
            }
            
                        
            if($filePath){
                $request->getSession()->getFlashBag()->add('success', 'Vidéo convertie avec succès !');
                return $this->file($filePath);

            }else{
                $request->getSession()->getFlashBag()->add('danger', $error);
                return $this->RedirectToRoute('convertor');
            }

        }
        
        
        return $this->render('my/convertor.html.twig',array(
            'notificationList' => $notificationList,
            'form' => $form->createView(),
        ));
    }


}
