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
        $event1->setName('Cervezaaa');
        $event1->setAddress('12 Avenue de Suffren, Paris, 75015');
        $event1->setStartDate(new \DateTime());
        $event1->setEndDate((new \DateTime())->add(new DateInterval('PT4H30M')));
        $event1->setCreatedDate(new \DateTime());
        $event1->setDescription('P\'tite bière zoo');
        $event1->setCreator($user0);
        $event1->setLatitude(48.851100);
        $event1->setLongitude(2.301080);
        $manager->persist($event1);
        
        $event2 = new Event();
        $event2->setName('Remise Des Diplomes');
        $event2->setAddress('Maison de la Mutualité, Rue Saint-Victor, Paris');
        $event2->setStartDate(new \DateTime('2020-03-20 17:00:00'));
        $event2->setEndDate(new \DateTime('2020-03-20 22:00:00'));
        $event2->setCreatedDate(new \DateTime());
        $event2->setDescription('P\'tite RDD zoo');
        $event2->setCreator($user0);
        $event2->setLatitude(48.848660);
        $event2->setLongitude(2.350300);
        $manager->persist($event2);
        
        $event3 = new Event();
        $event3->setName('Gala ECE');
        $event3->setAddress('Lieu Inconu');
        $event3->setStartDate(new \DateTime('2020-01-18 21:00:00'));
        $event3->setEndDate(new \DateTime('2020-01-19 06:00:00'));
        $event3->setCreatedDate(new \DateTime());
        $event3->setDescription('P\'tit Gala des 100 ans de l\'ECE zoo');
        $event3->setCreator($user0);
        $event3->setLatitude(48.848660);
        $event3->setLongitude(2.350500);
        $manager->persist($event3);
        
        $event4 = new Event();
        $event4->setName('Australian Grand Prix');
        $event4->setAddress('Albert Park Formula 1 Circuit VIC 3206 Australia, Melbourne Victoria, Australie');
        $event4->setStartDate(new \DateTime('2020-03-13 06:00:00'));
        $event4->setEndDate(new \DateTime('2020-03-15 07:10:00'));
        $event4->setCreatedDate(new \DateTime());
        $event4->setDescription('Premier Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 14/03 à 06h00 - Course à 05h10 zzzz');
        $event1->setCreator($user0);
        $event4->setLatitude(-37.851110);
        $event4->setLongitude(144.978380);
        $manager->persist($event4);
        
        //GP n°1
        $event5 = new Event();
        $event5->setName('Australian Grand Prix');
        $event5->setAddress('Albert Park Formula 1 Circuit VIC 3206 Australia, Melbourne Victoria, Australie');
        $event5->setStartDate(new \DateTime('2020-03-13 02:00:00'));
        $event5->setEndDate(new \DateTime('2020-03-15 08:10:00'));
        $event5->setCreatedDate(new \DateTime());
        $event5->setDescription('Premier Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 14/03 à 07h00 - Course à 06h10 zzzz');
        $event5->setCreator($user0);
        $event5->setLatitude(-37.851110);
        $event5->setLongitude(144.978380);
        $manager->persist($event5);
        
        $event6 = new Event();
        $event6->setName('Bahrain Grand Prix');
        $event6->setAddress('Circuit international de Sakhir');
        $event6->setStartDate(new \DateTime('2020-03-20 12:00:00'));
        $event6->setEndDate(new \DateTime('2020-03-22 18:10:00'));
        $event6->setCreatedDate(new \DateTime());
        $event6->setDescription('Deuxième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 21/03 à 16h00 - Course à 16h10');
        $event6->setCreator($user0);
        $event6->setLatitude(26.020910);
        $event6->setLongitude(50.490780);
        $manager->persist($event6);
        
        $event16 = new Event();
        $event16->setName('Vietnam Grand Prix');
        $event16->setAddress('Vietnam Grand Prix');
        $event16->setStartDate(new \DateTime('2020-04-03 05:00:00'));
        $event16->setEndDate(new \DateTime('2020-04-05 09:10:00'));
        $event16->setCreatedDate(new \DateTime());
        $event16->setDescription('Troisième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 04/04 à 09h00 - Course à 09h10');
        $event16->setCreator($user0);
        $event16->setLatitude(21.006830);
        $event16->setLongitude(105.766570);
        $manager->persist($event16);
        
        $event7 = new Event();
        $event7->setName('Chinese Grand Prix');
        $event7->setAddress('F1 Chinese Grand Prix, District de Jiading, Chine');
        $event7->setStartDate(new \DateTime('2020-04-17 04:00:00'));
        $event7->setEndDate(new \DateTime('2020-04-19 10:10:00'));
        $event7->setCreatedDate(new \DateTime());
        $event7->setDescription('Quatrième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 18/04 à 08h00 - Course à 08h10');
        $event7->setCreator($user0);
        $event7->setLatitude(23.467940);
        $event7->setLongitude(120.433960);
        $manager->persist($event7);
        
        $event17 = new Event();
        $event17->setName('Dutch Grand Prix');
        $event17->setAddress('Circuit de Zandvoort');
        $event17->setStartDate(new \DateTime('2020-05-01 13:00:00'));
        $event17->setEndDate(new \DateTime('2020-05-03 18:10:00'));
        $event17->setCreatedDate(new \DateTime());
        $event17->setDescription('Cinquième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 02/05 à 17h00 - Course à 16h10');
        $event17->setCreator($user0);
        $event17->setLatitude(52.371850);
        $event17->setLongitude(4.530260);
        $manager->persist($event17);

        $event9 = new Event();
        $event9->setName('Spanish Grand Prix');
        $event9->setAddress('Circuit de Barcelone');
        $event9->setStartDate(new \DateTime('2020-05-08 11:00:00'));
        $event9->setEndDate(new \DateTime('2020-05-10 17:10:00'));
        $event9->setCreatedDate(new \DateTime());
        $event9->setDescription('Sixième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 09/05 à 15h00 - Course à 15h10');
        $event9->setCreator($user0);
        $event9->setLatitude(41.551460);
        $event9->setLongitude(2.248460);
        $manager->persist($event9);
        
        $event10 = new Event();
        $event10->setName('Monaco Grand Prix');
        $event10->setAddress('Circuit de Monaco');
        $event10->setStartDate(new \DateTime('2020-05-21 11:00:00'));
        $event10->setEndDate(new \DateTime('2020-05-24 17:10:00'));
        $event10->setCreatedDate(new \DateTime());
        $event10->setDescription('Septième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 23/05 à 15h00 - Course à 15h10');
        $event10->setCreator($user0);
        $event10->setLatitude(43.734580);
        $event10->setLongitude(7.422330);
        $manager->persist($event10);
        
        $event8 = new Event();
        $event8->setName('Azerbaijan Grand Prix');
        $event8->setAddress('Circuit de Baku');
        $event8->setStartDate(new \DateTime('2020-06-05 11:00:00'));
        $event8->setEndDate(new \DateTime('2020-06-07 16:10:00'));
        $event8->setCreatedDate(new \DateTime());
        $event8->setDescription('Huitième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 06/06 à 15h00 - Course à 14h10');
        $event8->setCreator($user0);
        $event8->setLatitude(40.3725);
        $event8->setLongitude(49.853333);
        $manager->persist($event8);
        
        $event11 = new Event();
        $event11->setName('Canadian Grand Prix');
        $event11->setAddress('Circuit Gilles-Villeneuve');
        $event11->setStartDate(new \DateTime('2020-06-12 16:00:00'));
        $event11->setEndDate(new \DateTime('2020-06-14 22:10:00'));
        $event11->setCreatedDate(new \DateTime());
        $event11->setDescription('Neuvième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 13/06 à 20h00 - Course à 20h10');
        $event11->setCreator($user0);
        $event11->setLatitude(45.545240);
        $event11->setLongitude(-75.411080);
        $manager->persist($event11);
        
        $event12 = new Event();
        $event12->setName('French Grand Prix');
        $event12->setAddress('Circuit Automobile Paul Ricard');
        $event12->setStartDate(new \DateTime('2020-06-26 11:00:00'));
        $event12->setEndDate(new \DateTime('2020-06-28 17:10:00'));
        $event12->setCreatedDate(new \DateTime());
        $event12->setDescription('Dixième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 27/06 à 15h00 - Course à 15h10');
        $event12->setCreator($user0);
        $event12->setLatitude(43.202460);
        $event12->setLongitude(5.777210);
        $manager->persist($event12);
        
        $event13 = new Event();
        $event13->setName('Austrian Grand Prix');
        $event13->setAddress('Circuit de Spielberg');
        $event13->setStartDate(new \DateTime('2020-07/03 11:00:00'));
        $event13->setEndDate(new \DateTime('2020-07-05 17:10:00'));
        $event13->setCreatedDate(new \DateTime());
        $event13->setDescription('Onzième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 04/07 à 15h00 - Course à 15h10');
        $event13->setCreator($user0);
        $event13->setLatitude(47.207120);
        $event13->setLongitude(14.796850);
        $manager->persist($event13);
        
        $event14 = new Event();
        $event14->setName('British Grand Prix');
        $event14->setAddress('Circuit de Silverstone');
        $event14->setStartDate(new \DateTime('2020-07-17 11:00:00'));
        $event14->setEndDate(new \DateTime('2020-07-19 17:10:00'));
        $event14->setCreatedDate(new \DateTime());
        $event14->setDescription('Douzième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 18/07 à 15h00 - Course à 15h10');
        $event14->setCreator($user0);
        $event14->setLatitude(53.502610);
        $event14->setLongitude(-1.276870);
        $manager->persist($event14);
        
        $event15 = new Event();
        $event15->setName('Hungarian Grand Prix');
        $event15->setAddress('Hungaroring');
        $event15->setStartDate(new \DateTime('2020-07-31 11:00:00'));
        $event15->setEndDate(new \DateTime('2020-08-02 17:10:00'));
        $event15->setCreatedDate(new \DateTime());
        $event15->setDescription('Treizième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 01/08 à 15h00 - Course à 15h10');
        $event15->setCreator($user0);
        $event15->setLatitude(47.596900);
        $event15->setLongitude(19.248010);
        $manager->persist($event15);
        
        $event16 = new Event();
        $event16->setName('Belgian Grand Prix');
        $event16->setAddress('Circuit de Spa-Francorchamps');
        $event16->setStartDate(new \DateTime('2020-08-28 11:00:00'));
        $event16->setEndDate(new \DateTime('2020-08-30 17:10:00'));
        $event16->setCreatedDate(new \DateTime());
        $event16->setDescription('Quatorzième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 29/08 à 15h00 - Course à 15h10');
        $event16->setCreator($user0);
        $event16->setLatitude(50.394180);
        $event16->setLongitude(5.929000);
        $manager->persist($event16);
        
        $event17 = new Event();
        $event17->setName('Italian Grand Prix');
        $event17->setAddress('Circuit Internationnal de Monza');
        $event17->setStartDate(new \DateTime('2020-09-04 11:00:00'));
        $event17->setEndDate(new \DateTime('2020-09-06 17:10:00'));
        $event17->setCreatedDate(new \DateTime());
        $event17->setDescription('Quinzième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 05/09 à 15h00 - Course à 15h10');
        $event17->setCreator($user0);
        $event17->setLatitude(45.572490);
        $event17->setLongitude(9.283500);
        $manager->persist($event17);
        
        $event18 = new Event();
        $event18->setName('Singapore Grand Prix');
        $event18->setAddress('Circuit de Singapour');
        $event18->setStartDate(new \DateTime('2020-09-18 10:30:00'));
        $event18->setEndDate(new \DateTime('2020-09-20 16:10:00'));
        $event18->setCreatedDate(new \DateTime());
        $event18->setDescription('Seizième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 19/09 à 15h00 - Course à 14h10');
        $event18->setCreator($user0);
        $event18->setLatitude(1.300310);
        $event18->setLongitude(103.864330);
        $manager->persist($event18);
        
        $event19 = new Event();
        $event19->setName('Russian Grand Prix');
        $event19->setAddress('Autodrome de Sotchi');
        $event19->setStartDate(new \DateTime('2020-09-25 10:00:00'));
        $event19->setEndDate(new \DateTime('2020-09-27 13:10:00'));
        $event19->setCreatedDate(new \DateTime());
        $event19->setDescription('Dix-Septième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 26/09 à 14h00 - Course à 13h10');
        $event19->setCreator($user0);
        $event19->setLatitude(43.425670);
        $event19->setLongitude(40.003720);
        $manager->persist($event19);
        
        $event20 = new Event();
        $event20->setName('Japanese Grand Prix');
        $event20->setAddress('Circuit de Suzuka');
        $event20->setStartDate(new \DateTime('2020-10-09 03:00:00'));
        $event20->setEndDate(new \DateTime('2020-10-11 07:10:00'));
        $event20->setCreatedDate(new \DateTime());
        $event20->setDescription('Dix-Huitième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 10/10 à 08h00 - Course à 05h10');
        $event20->setCreator($user0);
        $event20->setLatitude(34.854057);
        $event20->setLongitude(136.570367);
        $manager->persist($event20);
        
        $event21 = new Event();
        $event21->setName('United States Grand Prix');
        $event21->setAddress('Circuit des Amériques');
        $event21->setStartDate(new \DateTime('2020-10-23 18:00:00'));
        $event21->setEndDate(new \DateTime('2020-10-25 21:10:00'));
        $event21->setCreatedDate(new \DateTime());
        $event21->setDescription('Dix-Neuvième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 24/10 à 11h00 - Course à 19h10');
        $event21->setCreator($user0);
        $event21->setLatitude(30.135800);
        $event21->setLongitude(-97.646200);
        $manager->persist($event21);
        
        $event22 = new Event();
        $event22->setName('Mexican Grand Prix');
        $event22->setAddress('Autódromo Hermanos Rodríguez');
        $event22->setStartDate(new \DateTime('2020-10-30 17:00:00'));
        $event22->setEndDate(new \DateTime('2020-11-01 22:10:00'));
        $event22->setCreatedDate(new \DateTime());
        $event22->setDescription('Vingtième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 31/10 à 20h00 - Course à 20h10');
        $event22->setCreator($user0);
        $event22->setLatitude(19.407200);
        $event22->setLongitude(-99.106190);
        $manager->persist($event22);
        
        $event23 = new Event();
        $event23->setName('Brazilian Grand Prix');
        $event23->setAddress('Autodromo José Carlos Pace');
        $event23->setStartDate(new \DateTime('2020-11-13 14:00:00'));
        $event23->setEndDate(new \DateTime('2020-11-15 20:10:00'));
        $event23->setCreatedDate(new \DateTime());
        $event23->setDescription('Vingt-et-unième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 14/11 à 19h00 - Course à 18h10');
        $event23->setCreator($user0);
        $event23->setLatitude(-23.703760);
        $event23->setLongitude(-46.700920);
        $manager->persist($event23);
        
        $event24 = new Event();
        $event24->setName('Abu Dhabi Grand Prix');
        $event24->setAddress('Yas Marina Circuit');
        $event24->setStartDate(new \DateTime('2020-11-27 10:00:00'));
        $event24->setEndDate(new \DateTime('2020-11-29 16:10:00'));
        $event24->setCreatedDate(new \DateTime());
        $event24->setDescription('Vingt-Deuxième Grand Prix de l\'année !\nAnd Away we gooo!! Qualif : 28/11 à 14h00 - Course à 14h10');
        $event24->setCreator($user0);
        $event24->setLatitude(32.797250);
        $event24->setLongitude(35.103060);
        $manager->persist($event24);
        
        $event25 = new Event();
        $event25->setName('Pik Pik Party');
        $event25->setAddress('8 rue Emile Locq, Cauvigny');
        $event25->setStartDate(new \DateTime('2020-03-26 10:00:00'));
        $event25->setEndDate((new \DateTime('2020-03-29 10:00:00')));
        $event25->setCreatedDate(new \DateTime());
        $event25->setDescription('P\'tite pik pik zooo\n Préparez vos fois comme d\'abb');
        $event25->setCreator($user0);
        $event25->setLatitude(48.851100);
        $event25->setLongitude(2.301080);
        $manager->persist($event25);
        
        
        //Job Fixtures
        $job0 = new Job();
        $job0->setAddress('118 rue de Paris, Boulogne-Billancourt, 92100');
        $job0->setCompanyName('Catan');
        $job0->setStartDate(new \DateTime());
        $job0->setLatitude(2.238590);
        $job0->setLongitude(48.841520);
        $job0->setUser($user0);
        $manager->persist($job0);
        
        $job1 = new Job();
        $job1->setAddress('EY, Tour First, Place des Saisons, Paris');
        $job1->setCompanyName('EY');
        $job1->setStartDate(new \DateTime());
        $job1->setLatitude(2.252560);
        $job1->setLongitude(48.889920);
        $job1->setUser($user5);
        $manager->persist($job1);


        
        //Recommandation Fixtures
        $recommandation0= new Recommandation();
        $recommandation0->setName('Cristal');
        $recommandation0->setType('bar');
        $recommandation0->setUser($user0);
        $recommandation0->setAddress('12 Avenue de Suffren, Paris, 75015');
        $manager->persist($recommandation0);
        
        //persist all users and entities remaining
        $manager->persist($user0);
        $manager->persist($user5);
        //flush database
        $manager->flush();
    }
    
}
