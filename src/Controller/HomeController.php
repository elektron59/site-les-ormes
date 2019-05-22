<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomeController extends Controller{

    /**
     * @Route("/bonjour/{prenom}/age/{age}", name="hello")
     * @Route("/bonjour", name="hello_base")
     * @Route("/bonjour/{prenom}", name="hello_prenom")
     * Montre la page qui dit bonjour
     * 
     * @return void
     */
    public function hello($prenom = "anonyme", $age = 0) {
        return $this ->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
        );
    }

    /**
     * @Route("/", name="accueil")
     */

    public function home(){
        $prenoms = ["Laurent" => 52, "Georges" => 72, "Marcel" => 32, "Jeanne" => 21];
        return $this->render(
            'home.html.twig',
            [
                'title' => "Bonjour à tout le monde",
                'age' => 12,
                'tableau' => $prenoms
            ]
        );
    }

}

?>