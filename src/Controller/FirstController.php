<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{
    #[Route('/first/celian', name: 'app_first')]
    public function index(): Response
    {

        return $this->render('first/index.html.twig', [
            'name'=>'laumond',
            'firstName'=>'celian'

        ]);
    }
    #[Route('/bonjour/{name}', name: 'bonjour')]
    public function bonjour($name):Response
    {
        return $this->render('first/bonjour.html.twig',['nom'=>$name]);

    }
    #[Route('/multiplication/{entier1<\d+>}/{entier2<\d+>}', name: 'multi')]
    public function multiplication($entier1,$entier2){
        $resultat=$entier1*$entier2;
        return new Response("<h1>$resultat</h1>");

    }
}
