<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Events\AddPersonneEvents;
use App\Form\PersonneFormType;
use App\Service\Helpers;
use App\Service\PdfService;
use App\Service\UploaderServices;
use Doctrine\Persistence\ManagerRegistry;
use Psr\EventDispatcher\EventDispatcherInterface;
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

    public function __construct(private LoggerInterface $logger,private Helpers $helpers,private EventDispatcherInterface $dispatcher)
    {
    }

    #[Route('/', name: 'personne.list')]
    public function index(ManagerRegistry $doctrine)
    {

        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->findAll();
        return $this->render('personne/index.html.twig', ['personne' => $personne]);

    }
    #[Route('/pdf/{id}', name: 'personne.pdf')]
    public function generePDF(ManagerRegistry $doctrine,$id, PdfService $pdf)
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personne = $repository->find($id);
        if (!$personne) {
            // Gérer le cas où l'objet $personne est nul
            // Par exemple, rediriger vers une autre page ou afficher un message d'erreur
            return $this->redirectToRoute('personne.list.all');
        }


        $code = $this->render('personne/detail.html.twig', ['personne' => $personne])->getContent();
        $pdf->showPdf($code);

        // Autres actions si nécessaire

        return new Response(); // Vous pouvez retourner une réponse vide si vous n'avez pas besoin de renvoyer une vue spécifique
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
    public function detail(ManagerRegistry $doctrine, $id,PdfService $pdf)
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
    public function addPersonne(int $id, ManagerRegistry $doctrine, Request $request, UploaderServices $uploaderService): Response
    {
        $entityManager = $doctrine->getManager();

        if ($id !== 0) {
            $personne = $entityManager->getRepository(Personne::class)->find($id);

            if (!$personne) {
                $new = false;
                throw $this->createNotFoundException('Personne not found');
            }
        } else {
            $personne = new Personne();
            $new = true;
        }

        $form = $this->createForm(PersonneFormType::class, $personne);
        $form->remove('createdAt');
        $form->remove('updateAt');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($personne);
            $image = $form->get('image')->getData();

            // This condition is needed because the 'image' field is not required
            // so the image file must be processed only when a file is uploaded
            if ($image) {
                $directory = $this->getParameter('personne_directory');
                $personne->setImage($uploaderService->uploadImage($image, $directory));
            }
            if ($new) {
                $personne->setCreatedBy($this->getUser());
            }
            $entityManager->flush();
            if ($new) {
                $addPersonneEvent=new AddPersonneEvents($personne);
                $this->dispatcher->dispatch($addPersonneEvent,AddPersonneEvents::ADD_PERSONNE_EVENT);
            }
            $this->addFlash('success', "L'utilisateur a été ajouté avec succès");
            return $this->redirectToRoute('personne.list');
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
