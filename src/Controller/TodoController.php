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
    #[Route('/todo/add/{cle}/{element}', name: 'app_add_todo')]
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
    #[Route('/todo/modifier/{cle}/{element}', name: 'app_modify_todo')]
    public function modifietodo(Request $request,$cle,$element){
    $session=$request->getSession();
    if ($session->has('tableau')) {
        $tableau = $session->get('tableau');
        if (!isset ($tableau[$cle])) {
            $this->addFlash('erreur', "une cle :$cle  n'existe pas ");
        }
        else{
                $tableau[$cle] = $element;
                $session->set('tableau', $tableau);
                $this->addFlash('succes', "la $cle a recu $element");
                return $this->redirectToRoute('app_todo');
            }
        } else {
            $this->addFlash('erreur', "il n'y a pas de tableau en variable de sesion");
        }
        return $this->redirectToRoute('app_todo');

    }
    #[Route('/todo/delete/{cle}/', name: 'app_drop_todo')]
    public function supprimertodo(Request $request,$cle){
        $session=$request->getSession();
        if ($session->has('tableau')) {
            $tableau = $session->get('tableau');
            if (!isset ($tableau[$cle])) {
                $this->addFlash('erreur', "une cle :$cle  n'existe pas ");
            }
            else{
                unset($tableau[$cle]);
                $session->set('tableau', $tableau);
                $this->addFlash('succes', "la case de $cle a bien etait supprimer ");

            }
        }else {
            $this->addFlash('erreur', "il n'y a pas de tableau en variable de sesion");
        }
        return $this->redirectToRoute('app_todo');
    }
    #[Route('/todo/reset/', name: 'app_reset_todo')]
    public function resetTodo(Request $request){
        $session=$request->getSession();
        $session->remove('tableau');
        return $this->redirectToRoute('app_todo');
    }
}
