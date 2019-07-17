<?php

namespace App\Controller;

use App\Repository\MobilHomeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController{


    /**
     * @Route("/", name="accueil")
     */

    public function home(MobilHomeRepository $adRepo){
        return $this->render(
            'home.html.twig',
            [
                'ads' => $adRepo->findBestAds(4)
            ]
        );
    }

}

?>