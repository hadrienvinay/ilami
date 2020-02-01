<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use YoutubeDl\YoutubeDl;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;

class SongConverterService
{
    private $fileName = 0;
    
    public function convertSong(Request $request,$song, $path)
    {
        $dl = new YoutubeDl([
                'extract-audio' => true,
                'audio-format' => 'mp3',
                'audio-quality' => 0, // best
                'output' => '%(title)s.%(ext)s',
            ]);
        $dl->setDownloadPath($path);
        $dl->setBinPath('C:\wamp64\www\ilami\bin\youtube-dl');    
        try {
            $video = $dl->download($song);
            $this->fileName = $video->getFileName();
        } catch (NotFoundException $e) {
            $error = "Cette vidéo n'existe pas";
        } catch (PrivateVideoException $e) {
            $error = "Cette vidéo est privée";
        } catch (CopyrightException $e) {
            $error = "Copyright Error";
        } catch (\Exception $e) {
            $error = "Erreur lors du téléchargement".$e;
        }
        if(!$this->fileName){
            $request->getSession()->getFlashBag()->add('danger', $error);
            return 0;
        }
        else {
            return $this->fileName;
        }
        
    }

}

