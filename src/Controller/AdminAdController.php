<?php

namespace App\Controller;

use App\Entity\MobilHome;
use App\Form\AnnonceType;
use App\Repository\MobilHomeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads", name="admin_ads_index")
     */
    public function index(MobilHomeRepository $repo)
    {
        $ads = $repo->findAll();
        return $this->render('admin/ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param MobilHome $ad
     * @return Response
     */
    public function edit(MobilHome $ad, Request $request, ObjectManager $manager){
        $form = $this->createForm(AnnonceType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getNomMh()}</strong> a bien été enregistrée !"
            );
        }

        return $this->render('admin/ad/edit.html.twig',[
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     * 
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     *
     * @param MobilHome $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(MobilHome $ad, ObjectManager $manager) {
        if(count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getNomMh()}</strong> car elle possède déjà des réservations !"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getNomMh()}</strong> a bien été supprimée !"
            );
        }
        
        return $this->redirectToRoute('admin_ads_index');
    }
}
