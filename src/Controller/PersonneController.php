<?php

namespace App\Controller;

use App\Entity\Personne;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'personne.list')]
    public function index(ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->findAll();
        return $this->render('personne/index.html.twig', ['personne' => $personne]);

    }

    #[Route('/all/{pages?1}/{nbre?12}', name: 'personne.list.all')]
    public function indexall(ManagerRegistry $doctrine, $pages, $nbre)
    {
        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repository->count([]);
        $nbrePages = ceil(($nbPersonne / $nbre));
        $personne = $repository->findBy([], [], $nbre, ($pages - 1) * $nbre);
        return $this->render('personne/index.html.twig', [
            'personne' => $personne,
            'isPaginated' => true,
            'nbrePage' => $nbrePages,
            'page' => $pages,
            'nbre' => $nbre
        ]);

    }

    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(ManagerRegistry $doctrine, $id)
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);
        if (!$personne) {

            return $this->redirectToRoute('personne.list');
            $this->addFlash('error', "la personne id $id nexiste pas ");
        }
        return $this->render('personne/detail.html.twig', ['personne' => $personne]);

    }

    #[Route('/add', name: 'addpersonne')]
    public function addPersonne(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $personne = new Personne();
        $personne->setPrenom('celian');
        $personne->setNom('laumond');
        $personne->setAge('20');

        $personne2 = new Personne();
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
            'personne' => $personne
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function deletepersonne(ManagerRegistry $doctrine, $id): RedirectResponse
    {
        $personne = $doctrine->getRepository(Personne::class)->find($id);

        if ($personne) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($personne);
            $entityManager->flush();
            $this->addFlash('success', 'La personne a bien été supprimée.');
        } else {
            $this->addFlash('erreur', 'La personne n\'existe pas.');
        }

        return $this->redirectToRoute('personne.list.all');
    }



    #[Route('/modify/{id}/{nom}/{prenom}/{age}', name: 'update')]
    public function update(ManagerRegistry $doctrine, $id, $nom, $prenom, $age): RedirectResponse
    {
        $entityManager = $doctrine->getManager();
        $personne = $entityManager->getRepository(Personne::class)->find($id);

        if ($personne) {
            $personne->setNom($nom);
            $personne->setPrenom($prenom);
            $personne->setAge($age);

            $entityManager->persist($personne);
            $entityManager->flush();

            $this->addFlash('success', 'La personne a bien été modifiée.');
        } else {
            $this->addFlash('erreur', 'La personne n\'existe pas.');
        }

        return $this->redirectToRoute('personne.list.all');
    }

    #[Route('/all/age/{agemin}/{agemax}', name: 'personne.list.age')]
    public function selectAge(ManagerRegistry $doctrine, $agemin, $agemax)
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->FindPersonnesByAge($agemin,$agemax);
        return $this->render('personne/index.html.twig', ['personne' => $personne]);

    }
    #[Route('/stat/age/{agemin}/{agemax}', name: 'personne.list.stat')]
    public function statage(ManagerRegistry $doctrine, $agemin, $agemax)
    {
        $repository = $doctrine->getRepository(Personne::class);
        $stats = $repository->statPersonnesByAge($agemin,$agemax);

        return $this->render('personne/stats.html.twig', ['stats' => $stats[0],
        'ageMin'=>$agemin,
            'ageMax'=>$agemax
        ]);

    }
}
