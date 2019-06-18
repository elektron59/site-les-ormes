<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\MobilHome;
use App\Form\AnnonceType;
use App\Repository\MobilHomeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MobilHomeController extends AbstractController
{
    /**
     * @Route("/mobilhomes", name="mobilhome_location")
     */
    public function index(MobilHomeRepository $repo)
    {
        $mobilhomes = $repo->findAll(); // findAll() récupère tous les enregistrements de la table visée  

        return $this->render('mobil_home/index.html.twig', [
            'mobilhomes' => $mobilhomes
        ]);
    }

    /**
     * Permet de créer une annonce de location de Mobil home
     * 
     * @Route("/mobilhomes/new", name="annonce_create")
     *
     * @return Response
     */
    public function create(Request $request, ObjectManager $manager){
        $mobilhome = new MobilHome();

        $form = $this->createForm(AnnonceType::class, $mobilhome);

        $form->handleRequest($request); // La fonction handleRequest permet de parcourir la requête et d'extraire les informations du form

        if($form->isSubmitted() && $form->isValid()){ // Permet de savoir si le formulaire a été soumis ou pas et s'il est valide 
            // fait persister les images du sous formulaire
            foreach($mobilhome->getImages() as $image){
                $image->setMobilhome($mobilhome);
                $manager->persist($image);
            }

            $manager-> persist($mobilhome);// prévient Doctrine qu'on veut sauver
            $manager-> flush(); // envoie la requête SQL
            
            $this->addFlash(
                'success', // affichera le flash en vert
                "L'annonce <strong>{$mobilhome->getNomMh()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('annonces_show', [
                'slugMh'=> $mobilhome->getSlugMh()
            ]);// Crée une Response qui dmande une redirection sur une autre page
        }
        return $this->render('mobil_home/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/mobilhomes/{slugMh}/edit", name="annonces_edit")
     * 
     * @return Response
     */
    public function edit(MobilHome $mobilhome, Request $request,ObjectManager $manager){

        $form = $this->createForm(AnnonceType::class, $mobilhome);

        $form->handleRequest($request); // La fonction handleRequest permet de parcourir la requête et d'extraire les informations du form

        if($form->isSubmitted() && $form->isValid()){ // Permet de savoir si le formulaire a été soumis ou pas et s'il est valide 
            // fait persister les images du sous formulaire
            foreach($mobilhome->getImages() as $image){
                $image->setMobilhome($mobilhome);
                $manager->persist($image);
            }

            $manager-> persist($mobilhome);// prévient Doctrine qu'on veut sauver
            $manager-> flush(); // envoie la requête SQL
            
            $this->addFlash(
                'success', // affichera le flash en vert
                "Les modifications de l'annonce <strong>{$mobilhome->getNomMh()}</strong> ont bien été enregistrées !"
            );
            return $this->redirectToRoute('annonces_show', [
                'slugMh'=> $mobilhome->getSlugMh()
            ]);// Crée une Response qui dmande une redirection sur une autre page
        }

        return $this->render('mobil_home/edit.html.twig' , [
            'form' => $form->createView(),
            'mobilhome' => $mobilhome
        ]);
    }

    /**
     * Permet d'afficher une seule annonce de mobil home
     * 
     * @Route("/mobilhomes/{slugMh}", name="annonces_show")
     * 
     * @return Response
     */
    // je récupère l'annonce' qui correspond au slug !
    public function show(MobilHome $annonce){
            // Je récupère l'annonce qui correspond au slug !
            //$annonce = $repo->findOneBySlugMh($slugMh);

            return $this->render('mobil_home/show_annonce_mh.html.twig', [
                'annonce' => $annonce
        ]);
    }


}
