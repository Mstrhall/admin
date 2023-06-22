<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneFormType;
use App\Service\Helpers;
use App\Service\UploaderServices;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request; // Importez la classe correcte
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/personne')]
class PersonneController extends AbstractController
{

    public function __construct(private LoggerInterface $logger,private Helpers $helpers)
    {
    }

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

        echo $this->helpers->saycc();
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

    #[Route('/edit/{id?0}', name: 'edit.personne')]
    public function addPersonne(int $id, ManagerRegistry $doctrine, Request $request,UploaderServices $uploaderService): Response
    {
        $entityManager = $doctrine->getManager();

        if ($id !== 0) {
            $personne = $entityManager->getRepository(Personne::class)->find($id);

            if (!$personne) {
                throw $this->createNotFoundException('Personne not found');
            }
        } else {
            $personne = new Personne();
        }

        $form = $this->createForm(PersonneFormType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updateAt');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($personne);
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $directory= $this->getParameter('personne_directory');
                $personne->setimage($uploaderService->uploadImage($image,$directory));
            }
            $entityManager->flush();

            $this->addFlash('success', "L'utilisateur a été ajouté avec succès");
        }

        return $this->render('personne/add-personne.html.twig', [
            'form' => $form->createView()
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
