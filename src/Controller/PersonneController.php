<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne.list')]
    public function index(ManagerRegistry $doctrine){
        $repository=$doctrine->getRepository(Personne::class);
        $personne=$repository->findAll();
        return $this->render('personne/index.html.twig',['personne'=>$personne]);

    }
    #[Route('/personne', name: 'app_personne')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager=$doctrine->getManager();
        $personne=new Personne();
        $personne->setPrenom('celian');
        $personne->setNom('laumond');
        $personne->setAge('20');

        $personne2=new Personne();
        $personne2->setPrenom('philippe');
        $personne2->setNom('laumond');
        $personne2->setAge('53');

        //ajouter l'operation d'insertion de la personne dans ma transaction
        $entityManager->persist($personne);
        $entityManager->persist($personne2);
        //execution de la requete
        $entityManager->flush();
        return $this->render('personne/detail.html.twig', [
            'controller_name' => 'PersonneController',
            'personne'=>$personne
        ]);
    }
}
