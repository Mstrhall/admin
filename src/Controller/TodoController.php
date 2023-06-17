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
            $this->addFlash('info',"la liste des todo vien d'etre initialiser");
        }
        return $this->render('todo/index.html.twig');
    }
    #[Route('/todo/{cle}/{element}', name: 'app_add_todo')]
    public function addtodo(Request $request,$cle,$element){
        $session=$request->getSession();
        if ($session->has('tableau')){
            $tableau=$session->get('tableau');
            if(isset ($tableau[$cle])){
                $this->addFlash('erreur',"une cle :$cle  a deja etait crée ");

            }else{
                $tableau[$cle]=$element;
                $session->set('tableau',$tableau);
                $this->addFlash('succes',"la $cle a recu $element" );
                return $this->redirectToRoute('app_todo');
            }

        }
        else{
            $this->addFlash('erreur',"il n'y a pas de tableau en variable de sesion");
        }
        return $this->redirectToRoute('app_todo');


    }



}
