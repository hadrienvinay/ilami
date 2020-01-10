<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Event;
use App\Entity\Album;
use App\Entity\Recommandation;
use App\Entity\Job;


class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        //User Fixtures
$user0 = new User();
        $user0->setEnabled(true);
        $user0->setName('Vinay');
        $user0->setPrename('Hadrien');
        $user0->setUsername('suri');
        $user0->addRole("ROLE_ADMIN");
        $password = $this->encoder->encodePassword($user0, 'aa');
        $user0->setPassword($password);
        $user0->setEmail('hadrien.vinay@yahoo.fr');
        $user0->setBirthdate(new \DateTime('1996-08-24'));
        $user0->setPhone('0643079512');
        $user0->setTeam('Imalipusram');
        $user0->setStatus('RIP le Chômage');
        $user0->setAddress('118 rue de Paris, Boulogne-Billancourt, 92100');
        $user0->setLongitude(2.238590);
        $user0->setLatitude(48.841520);
        $user0->setUpdatedDate(new \DateTime());
        $manager->persist($user0);
        
        $user1 = new User();
        $user1->setEnabled(true);
        $user1->setName('Urzua');
        $user1->setPrename('Andrès');
        $user1->setUsername('chewi');
        $password = $this->encoder->encodePassword($user1, 'pass');
        $user1->setPassword($password);
        $user1->setEmail('andres.urzua@yahoo.fr');
        $user1->setBirthdate(new \DateTime('1997-01-27'));
        $user1->setPhone('0752138587');
        $user1->setTeam('Imalipusram');
        $user1->setStatus('Me gusta las chilaquiles !');
        $user1->setAddress('42 Boulevard Ney, Paris');
        $user1->setLongitude(2.366030);
        $user1->setLatitude(48.898770);
        $user1->setUpdatedDate(new \DateTime());
        $manager->persist($user1);
        
        $user2 = new User();
        $user2->setEnabled(true);
        $user2->setName('Falcon');
        $user2->setPrename('Arthur');
        $user2->setUsername('vagabond');
        $password = $this->encoder->encodePassword($user2, 'pass');
        $user2->setPassword($password);
        $user2->setEmail('arthur.falcon@gmail.fr');
        $user2->setBirthdate(new \DateTime('1996-08-14'));
        $user2->setPhone('0665056896');
        $user2->setTeam('Imalipusram');
        $user2->setStatus('Ca vagabonde en masse');
        $user2->setAddress('8 Rue Cluseret, Suresnes');
        $user2->setLongitude(2.220930);
        $user2->setLatitude(48.867940);
        $user2->setUpdatedDate(new \DateTime());
        $manager->persist($user2);
        
        $user3 = new User();
        $user3->setEnabled(true);
        $user3->setName('Falcon');
        $user3->setPrename('Basile');
        $user3->setUsername('jazzy');
        $password = $this->encoder->encodePassword($user3, 'pass');
        $user3->setPassword($password);
        $user3->setEmail('basile.falcon@yahoo.fr');
        $user2->setBirthdate(new \DateTime('1996-08-14'));
        $user3->setPhone('0634059509');
        $user3->setTeam('Imalipusram');
        $user3->setStatus('Et tu roules un dragon!');
        $user3->setAddress('11 Rue Madame de Sévigné, 54000, Laval');
        $user3->setLongitude(-0.784130);
        $user3->setLatitude(48.072040);
        $user3->setUpdatedDate(new \DateTime());
        $manager->persist($user3);
        
        $user4 = new User();
        $user4->setEnabled(true);
        $user4->setName('Eschasseriau');
        $user4->setPrename('Laura');
        $user4->setUsername('laulau');
        $password = $this->encoder->encodePassword($user4, 'pass');
        $user4->setPassword($password);
        $user4->setEmail('laura.eschasseriau@yahoo.fr');
        $user4->setBirthdate(new \DateTime('1994-09-08'));
        $user4->setPhone('0629857463');
        $user4->setTeam('Imalipusram');
        $user4->setStatus('Me gusta las chilaquiles !');
        $user4->setAddress('65 Rue Jean de la Fontaine, 75016 Paris, France');
        $user4->setLongitude(2.267400);
        $user4->setLatitude(48.849320);
        $user4->setUpdatedDate(new \DateTime());
        $manager->persist($user4);
        
        $user5 = new User();
        $user5->setEnabled(true);
        $user5->setName('Pechere');
        $user5->setPrename('Flavien');
        $user5->setUsername('flavb');
        $password = $this->encoder->encodePassword($user5, 'pass');
        $user5->setPassword($password);
        $user5->setEmail('flav_p@hotmail.fr');
        $user5->setBirthdate(new \DateTime('1995-07-23'));
        $user5->setPhone('0651766869');
        $user5->setTeam('Imalipusram');
        $user5->setStatus('J\'ai vu ses lolos !');
        $user4->setAddress('65 Rue Jean de la Fontaine, 75016 Paris, France');
        $user5->setLongitude(2.267400);
        $user5->setLatitude(48.849320);
        $user5->setUpdatedDate(new \DateTime());
        $manager->persist($user5);
        
        $user6 = new User();
        $user6->setEnabled(true);
        $user6->setName('Delval');
        $user6->setPrename('Léo');
        $user6->setUsername('lélé');
        $password = $this->encoder->encodePassword($user6, 'pass');
        $user6->setPassword($password);
        $user6->setEmail('leo.delval@yahoo.fr');
        $user6->setBirthdate(new \DateTime('1996-12-16'));
        $user6->setPhone('0634671603');
        $user6->setTeam('Imalipusram');
        $user6->setStatus('Viens içi que j\'te Calisse lo');
        $user6->setAddress('Montréal, QC, Canada');
        $user6->setLongitude(-73.553360);
        $user6->setLatitude(45.509060);
        $user6->setUpdatedDate(new \DateTime());
        $manager->persist($user6);
        
        $user7 = new User();
        $user7->setEnabled(true);
        $user7->setName('Sadone');
        $user7->setPrename('Alexandre');
        $user7->setUsername('mcmayo');
        $password = $this->encoder->encodePassword($user7, 'pass');
        $user7->setPassword($password);
        $user7->setEmail('alex.sadone@yahoo.fr');
        $user7->setBirthdate(new \DateTime('1996-11-30'));
        $user7->setPhone('0651033349');
        $user7->setTeam('Imalipusram');
        $user7->setStatus('I fuking love Kentish Toooown !');
        $user7->setAddress('Cherbury Street, London Borough of Hackney, England, United Kingdom');
        $user7->setLongitude(0.087640);
        $user7->setLatitude(51.507940);
        $user7->setUpdatedDate(new \DateTime());
        $manager->persist($user7);
        
        $user8 = new User();
        $user8->setEnabled(true);
        $user8->setName('Delaporte');
        $user8->setPrename('Grégoire');
        $user8->setUsername('chewi');
        $password = $this->encoder->encodePassword($user8, 'pass');
        $user8->setPassword($password);
        $user8->setEmail('greg.delaporte@yahoo.fr');
        $user8->setBirthdate(new \DateTime('1995-09-30'));
        $user8->setPhone('0671989345');
        $user8->setTeam('Imalipusram');
        $user8->setStatus('Peak(y) Coders Nation');
        $user7->setAddress('Cherbury Street, London Borough of Hackney, England, United Kingdom');
        $user8->setLongitude(0.087640);
        $user8->setLatitude(51.507940);
        $user8->setUpdatedDate(new \DateTime());
        $manager->persist($user8);
        
        $user9 = new User();
        $user9->setEnabled(true);
        $user9->setName('Lefort');
        $user9->setPrename('Marine');
        $user9->setUsername('mareine');
        $password = $this->encoder->encodePassword($user9, 'pass');
        $user9->setPassword($password);
        $user9->setEmail('marine.lft@yahoo.fr');
        $user9->setBirthdate(new \DateTime('1995-04-15'));
        $user9->setPhone('0752138587');
        $user9->setTeam('Imalipusram');
        $user9->setStatus('Vegan Fox :o');
        $user9->setAddress('7 Avenue Poniatowski, Maisons-Laffitte');
        $user9->setLongitude(2.140300);
        $user9->setLatitude(48.956920);
        $user9->setUpdatedDate(new \DateTime());
        $manager->persist($user9);
        
        $user10 = new User();
        $user10->setEnabled(true);
        $user10->setName('Folly');
        $user10->setPrename('Roch');
        $user10->setUsername('rocho');
        $password = $this->encoder->encodePassword($user10, 'pass');
        $user10->setPassword($password);
        $user10->setEmail('roch.folly@yahoo.fr');
        $user10->setBirthdate(new \DateTime('1997-08-25'));
        $user10->setPhone('0752138587');
        $user10->setTeam('Imalipusram');
        $user10->setStatus('Boston viee');
        $user10->setAddress('Boston, MA, United States of America');
        $user10->setLongitude(-71.058630);
        $user10->setLatitude(42.358990);
        $user10->setUpdatedDate(new \DateTime());
        $manager->persist($user10);
        
        $user11 = new User();
        $user11->setEnabled(true);
        $user11->setName('Tannous');
        $user11->setPrename('Christopher');
        $user11->setUsername('chris');
        $password = $this->encoder->encodePassword($user11, 'pass');
        $user11->setPassword($password);
        $user11->setEmail('chris.tannous@yahoo.fr');
        $user11->setBirthdate(new \DateTime('1996-08-28'));
        $user11->setPhone('0627757307');
        $user11->setTeam('Imalipusram');
        $user11->setStatus('Allez laaaaaaa');
        $user11->setAddress('10 Rue de Lourmel, Paris');
        $user11->setLongitude(2.291770);
        $user11->setLatitude(48.850090);
        $user11->setUpdatedDate(new \DateTime());
        $manager->persist($user11);
        
        $user12 = new User();
        $user12->setEnabled(true);
        $user12->setName('Goguel');
        $user12->setPrename('Arnaud');
        $user12->setUsername('nonow');
        $password = $this->encoder->encodePassword($user12, 'pass');
        $user12->setPassword($password);
        $user12->setEmail('arnaud.goguel@yahoo.fr');
        $user12->setBirthdate(new \DateTime('1995-10-17'));
        $user12->setPhone('0622101672');
        $user12->setTeam('Imalipusram');
        $user12->setStatus('Viens toucher mon p\'tit boule');
        $user12->setAddress('11 Rue Bargue, 75015 Paris');
        $user12->setLongitude(2.299560);
        $user12->setLatitude(48.841999);
        $user12->setUpdatedDate(new \DateTime());
        $manager->persist($user12);
        
        $user13 = new User();
        $user13->setEnabled(true);
        $user13->setName('Hure');
        $user13->setPrename('Gaultier');
        $user13->setUsername('zaïzaï');
        $password = $this->encoder->encodePassword($user13, 'pass');
        $user13->setPassword($password);
        $user13->setEmail('gaultier.hure@yahoo.fr');
        $user13->setBirthdate(new \DateTime('1995-07-12'));
        $user13->setPhone('0681750984');
        $user13->setTeam('Imalipusram');
        $user13->setStatus('Touche pas ma Bernie');
        $user13->setAddress('16 Rue de ville d\'Avray, 92310 Sèvres');
        $user13->setLongitude(2.207390);
        $user13->setLatitude(48.824820);
        $user13->setUpdatedDate(new \DateTime());
        $manager->persist($user13);
        
        $user14 = new User();
        $user14->setEnabled(true);
        $user14->setName('Dufort');
        $user14->setPrename('Alberic');
        $user14->setUsername('chewi');
        $password = $this->encoder->encodePassword($user14, 'pass');
        $user14->setPassword($password);
        $user14->setEmail('alberic.dufort@yahoo.fr');
        $user14->setBirthdate(new \DateTime('1996-04-29'));
        $user14->setPhone('0686406414');
        $user14->setTeam('Imalipusram');
        $user14->setStatus('Team Poulet Coco <3');
        $user14->setAddress('28-30 Rue Claude Terrasse, 75016 Paris, France');
        $user14->setLongitude(2.261580);
        $user14->setLatitude(48.838250);
        $user14->setUpdatedDate(new \DateTime());
        $manager->persist($user14);
        
        $user15 = new User();
        $user15->setEnabled(true);
        $user15->setName('Betouche');
        $user15->setPrename('Menad');
        $user15->setUsername('chewi');
        $password = $this->encoder->encodePassword($user15, 'pass');
        $user15->setPassword($password);
        $user15->setEmail('menad.betouche@yahoo.fr');
        $user15->setBirthdate(new \DateTime('1996-06-20'));
        $user15->setPhone('0752138587');
        $user15->setTeam('Imalipusram');
        $user15->setStatus('T\'as pas vu mes pecs ?');
        $user15->setAddress('42 Boulevard Ney, Paris');
        $user15->setLongitude(2.366050);
        $user15->setLatitude(48.898770);
        $user15->setUpdatedDate(new \DateTime());
        $manager->persist($user15);
        
        $user16 = new User();
        $user16->setEnabled(true);
        $user16->setName('Mirey');
        $user16->setPrename('Pauline');
        $user16->setUsername('chewi');
        $password = $this->encoder->encodePassword($user16, 'pass');
        $user16->setPassword($password);
        $user16->setEmail('pauline.mirey@yahoo.fr');
        $user16->setBirthdate(new \DateTime('1996-10-08'));
        $user16->setPhone('0752138587');
        $user16->setTeam('Imalipusram');
        $user16->setStatus('TPMT (Touche Pas Mon Thomaas)');
        $user16->setAddress('42 Boulevard Ney, Paris');
        $user16->setLongitude(2.366070);
        $user16->setLatitude(48.898770);
        $user16->setUpdatedDate(new \DateTime());
        $manager->persist($user16);
        
        $user17 = new User();
        $user17->setEnabled(true);
        $user17->setName('Aulanier');
        $user17->setPrename('Tanguy');
        $user17->setUsername('gitan');
        $password = $this->encoder->encodePassword($user17, 'pass');
        $user17->setPassword($password);
        $user17->setEmail('tanguy.aulanier@yahoo.fr');
        $user17->setBirthdate(new \DateTime('1996-12-27'));
        $user17->setPhone('0752138587');
        $user17->setTeam('Imalipusram');
        $user17->setStatus('Me gusta las chilaquiles !');
        $user17->setAddress('42 Boulevard Ney, Paris');
        $user17->setLongitude(2.366030);
        $user17->setLatitude(48.898770);
        $user17->setUpdatedDate(new \DateTime());
        $manager->persist($user17);
        
        $user18 = new User();
        $user18->setEnabled(true);
        $user18->setName('Pivette');
        $user18->setPrename('Guillaume');
        $user18->setUsername('piv');
        $password = $this->encoder->encodePassword($user18, 'pass');
        $user18->setPassword($password);
        $user18->setEmail('guillaume.pivette@yahoo.fr');
        $user18->setBirthdate(new \DateTime('1999-02-20'));
        $user18->setPhone('0641676954');
        $user18->setTeam('Imalipusram');
        $user18->setStatus('Vous ne passerez paaaas !');
        $user18->setAddress('157-161 Boulevard Charles de Gaulle, 92700 Colombes');
        $user18->setLongitude(2.226150);
        $user18->setLatitude(48.916060);
        $user18->setUpdatedDate(new \DateTime());
        $manager->persist($user18);
        
        $user19 = new User();
        $user19->setEnabled(true);
        $user19->setName('Tournade');
        $user19->setPrename('Aliénor');
        $user19->setUsername('lili');
        $password = $this->encoder->encodePassword($user19, 'pass');
        $user19->setPassword($password);
        $user19->setEmail('alienor.tournade@yahoo.fr');
        $user19->setBirthdate(new \DateTime('1996-01-04'));
        $user19->setPhone('0752138587');
        $user19->setTeam('Imalipusram');
        $user19->setStatus('Pot Pot?');
        $user19->setAddress('24 Rue de Chatou, 92700 Colombes, France');
        $user19->setLongitude(2.240900);
        $user19->setLatitude(48.915020);
        $user19->setUpdatedDate(new \DateTime());
        $manager->persist($user19);
        $manager->flush();

               

        //Event Fixtures
        $event0 = new Event();
        $event0->setName('Cristal');
        $event0->setAddress('12 Avenue de Suffren, Paris, 75015');
        $event0->setStartDate(new \DateTime('2019-07-22 17:00:00'));
        $event0->setEndDate(new \DateTime('2019-07-22 22:00:00'));
        $event0->setCreatedDate(new \DateTime());
        $event0->setDescription('P\'tit Cristal zoo');
        $event0->setCreator($user0);
        $event0->setLatitude(48.851100);
        $event0->setLongitude(2.301080);
        $manager->persist($event0);
        
        $event1 = new Event();
        $event1->setName('Australian Grand Prix');
        $event1->setAddress('Albert Park Formula 1 Circuit VIC 3206 Australia, Melbourne Victoria, Australie');
        $event1->setStartDate(new \DateTime('2020-03-13 06:00:00'));
        $event1->setEndDate(new \DateTime('2020-03-15 06:00:00'));
        $event1->setCreatedDate(new \DateTime());
        $event1->setDescription('Premier Grand Prix de l\'année !\nAnd Away we gooo!!');
        $event1->setCreator($user0);
        $event1->setLatitude(-37.851110);
        $event1->setLongitude(144.978380);
        $manager->persist($event1);
        $manager->persist($user0);
        
        $event2 = new Event();
        $event2->setName('Cervezaaa');
        $event2->setAddress('12 Avenue de Suffren, Paris, 75015');
        $event2->setStartDate(new \DateTime());
        $event2->setEndDate((new \DateTime())->add(new DateInterval('PT4H30M')));
        $event2->setCreatedDate(new \DateTime());
        $event2->setDescription('P\'tite bière zoo');
        $event2->setCreator($user0);
        $event2->setLatitude(48.851100);
        $event2->setLongitude(2.301080);
        $manager->persist($event2);
        
        $event3 = new Event();
        $event3->setName('Remise Des Diplomes');
        $event3->setAddress('Maison de la Mutualité, Rue Saint-Victor, Paris');
        $event3->setStartDate(new \DateTime('2020-03-20 17:00:00'));
        $event3->setEndDate(new \DateTime('2020-03-20 22:00:00'));
        $event3->setCreatedDate(new \DateTime());
        $event3->setDescription('P\'tite RDD zoo');
        $event3->setCreator($user0);
        $event3->setLatitude(48.848660);
        $event3->setLongitude(2.350300);
        $manager->persist($event3);
        
        $event4 = new Event();
        $event4->setName('Gala ECE');
        $event4->setAddress('Lieu Inconu');
        $event4->setStartDate(new \DateTime('2020-01-18 21:00:00'));
        $event4->setEndDate(new \DateTime('2020-01-19 06:00:00'));
        $event4->setCreatedDate(new \DateTime());
        $event4->setDescription('P\'tit Gala des 100 ans de l\'ECE zoo');
        $event4->setCreator($user0);
        $event4->setLatitude(48.848660);
        $event4->setLongitude(2.350500);
        $manager->persist($event4);
        
        //Job Fixtures
        $job0 = new Job();
        $job0->setAddress('118 rue de Paris, Boulogne-Billancourt, 92100');
        $job0->setCompanyName('Catan');
        $job0->setStartDate(new \DateTime());
        $job0->setLatitude(2.238590);
        $job0->setLongitude(48.841520);
        $job0->setUser($user0);
        $manager->persist($job0);
        $manager->persist($user0);
        
        $job1 = new Job();
        $job1->setAddress('EY, Tour First, Place des Saisons, Paris');
        $job1->setCompanyName('EY');
        $job1->setStartDate(new \DateTime());
        $job1->setLatitude(2.252560);
        $job1->setLongitude(48.889920);
        $job1->setUser($user5);
        $manager->persist($job1);
        $manager->persist($user5);


        
        //Recommandation Fixtures
        $recommandation0= new Recommandation();
        $recommandation0->setName('Cristal');
        $recommandation0->setType('bar');
        $recommandation0->setUser($user0);
        $recommandation0->setAddress('12 Avenue de Suffren, Paris, 75015');
        $manager->persist($recommandation0);
        $manager->persist($user0);

        
        $manager->flush();
    }
    
}
