<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use App\Entity\MobilHome;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        for($i = 1; $i <= 15; $i++) {
            $mobilHome = new MobilHome();

            $nom = $faker->sentence(); // génère une phrase aléatoire
            //$image = $faker->imageUrl(1000,350,'nature',true,'Domaine les Ormes');// Génère des images aléatoires, provenant de lorempixel.com, de type nature et signées "Domaine les Ormes"
            $detail = '<li>'.join('</li><li>',$faker->paragraphs(5)).'</li>'; // Génère une liste avec 5 lignes, contenant un paragraphe en Lorem
            $presentation = '<p>'.join('</p><p>',$faker->paragraphs(1)).'</p>'; // Génère une liste avec 5 lignes, contenant un paragraphe en Lorem
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
                        ->setPresentationMh($presentation);
            
            for($j = 1; $j <= mt_rand(2,5); $j++) { // la boucle va créer entre 2 et 5 images par mobil home
                $image = new Image();

                $image  ->setUrl($faker->imageUrl())
                        ->setLegende($faker->sentence())
                        ->setMobilhome($mobilHome);

                $manager->persist($image);
            }
                        

            // $product = new Product();
            // $manager->persist($product);

            $manager->persist($mobilHome);
        }
    
        $manager->flush();
    }
}
