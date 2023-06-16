<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'app_todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        //afficher notre tableau
        if (!$session->has('tableau')){
            $tableau=['achat'=>'acheter un yacht',
                'cours'=>'travailler mogodb',
                'travail'=>'trouver une alternance'
            ];
            $session->set('tableau',$tableau);
        }
        return $this->render('todo/index.html.twig');
    }
    #[Route('/todo/{cle}/{element}', name: 'app_todo')]
    public function addtodo(Request $request,$cle,$element){
        $session=$request->hasSession()


    }



}
