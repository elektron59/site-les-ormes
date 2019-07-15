<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Comment;
use App\Entity\MobilHome;
use App\Entity\Reservation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        // création d'un role Administrateur
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        // création d'un utilisateur avec le rôle Admin
        $adminUser = new User();
        $adminUser  ->setPrenom('Laurent')
                    ->setNom('Demet')
                    ->setEmail('laurent@demet.fr')
                    ->setHash($this->encoder->encodePassword($adminUser,'password'))
                    ->setAvatar('https://avatars.io/twitter/LaurentDemet')
                    ->setPresentation('<p>'.join('</p><p>', $faker->paragraphs(3)).'</p>')
                    ->addUserRole($adminRole);
        $manager->persist($adminUser);

        // Nous gérons les utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for($i = 1; $i <=10; $i++){
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99).'.jpg';

            if($genre == "male") $picture = $picture.'men/'.$pictureId;
            else $picture = $picture.'women/'.$pictureId;

            $presentation = '<p>'.join('</p><p>',$faker->paragraphs(3)).'</p>'; // Génère une liste avec 3 lignes, contenant un paragraphe en Lorem

            $hash = $this->encoder->encodePassword($user, 'password');

            $user   ->setPrenom($faker->firstname($genre))
                    ->setNom($faker->lastname)
                    ->setEmail($faker->email)
                    ->setPresentation($presentation)
                    ->setHash($hash)
                    ->setAvatar($picture);

            $manager->persist($user);
            $users[] = $user;
        }

        
        // Nous gérons les annonces
        for($i = 1; $i <= 15; $i++) {
            $mobilHome = new MobilHome();

            $nom = $faker->sentence(); // génère une phrase aléatoire
            //$image = $faker->imageUrl(1000,350,'nature',true,'Domaine les Ormes');// Génère des images aléatoires, provenant de lorempixel.com, de type nature et signées "Domaine les Ormes"
            $detail = '<li>'.join('</li><li>',$faker->paragraphs(5)).'</li>'; // Génère une liste avec 5 lignes, contenant un paragraphe en Lorem
            $presentation = '<p>'.join('</p><p>',$faker->paragraphs(1)).'</p>'; // Génère une liste avec 5 lignes, contenant un paragraphe en Lorem

            $user = $users[mt_rand(0, count($users)-1)];

            $mobilHome  ->setNomMh($nom)
                        ->setEmplacementMh("Parcelle numéro-$i")
                        ->setAnneeMh(mt_rand(2003, 2019)) // donne une année aléatoire entre 2003 et 2019
                        ->setSurfaceMh(mt_rand(25, 40)) // donne un nombre entre 25 et 40
                        ->setSurfaceEmplacementMh(mt_rand(25, 40))
                        ->setPhotoPrincipaleMh("/img/locations/mobil".mt_rand(1,15).".jpg")
                        ->setTarifJourHsMh(mt_rand(50, 60))
                        ->setTarifSemaineHsMh(mt_rand(250, 350))
                        ->setTarifJourBsMh(mt_rand(40, 50))
                        ->setTarifSemaineBsMh(mt_rand(200, 250))
                        ->setNbChambreMh(mt_rand(1, 3))
                        ->setNbPersonneMh(mt_rand(1, 6))
                        ->setDetailMh("<ul>$detail</ul>")
                        ->setPresentationMh($presentation)
                        ->setAuteur($user);
            
            for($j = 1; $j <= mt_rand(2,5); $j++) { // la boucle va créer entre 2 et 5 images par mobil home
                $image = new Image();

                $image  ->setUrl($faker->imageUrl())
                        ->setLegende($faker->sentence())
                        ->setMobilhome($mobilHome);

                $manager->persist($image);
            }

            // Gestion des réservations
            for ($j=1; $j <= mt_rand(0, 10); $j++) { 
                $reservation =  new Reservation();

                $createdAt = $faker->dateTimeBetween('-6 months'); // génère une date de moins de 6 mois
                $dateArrivee = $faker->dateTimeBetween('-3 months'); //génère une date de moins de 3 mois

                // Gestion de la date de fin
                $duree = mt_rand(3,10); // génère une durée entre 3 et 10 jours
                $dateDepart = (clone $dateArrivee)->modify("+$duree days");

                $montant    = $mobilHome->getTarifJourHsMh() * $duree;

                $client     = $users[mt_rand(0, count($users)-1)];
                $comment    = $faker->paragraph();

                $reservation    ->setClient($client)
                                ->setAnnonce($mobilHome)
                                ->setDateArrivee($dateArrivee)
                                ->setDateDepart($dateDepart)
                                ->setCreatedAt($createdAt)
                                ->setMontant($montant)
                                ->setCommentaire($comment);
                                

                $manager->persist($reservation);

                // Gestion des commentaires
                if(mt_rand(0,1)){
                    $comment=new Comment();
                    $comment    ->setContent($faker->paragraph())
                                ->setRating(mt_rand(1,5))
                                ->setAuthor($client)
                                ->setAd($mobilHome);

                    $manager    ->persist($comment);
                    
                }
            }
            

            // $product = new Product();
            // $manager->persist($product);

            $manager->persist($mobilHome);
        }
    
        $manager->flush();
    }
}
