<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TabController extends AbstractController
{
    #[Route('/tab/{nb?5}', name: 'app_tab')]
    public function index($nb): Response
    {
        $tab=[];
        for($i=0;$i<$nb;$i++){
            $tab[$i]=rand();
        }
        return $this->render('tab/index.html.twig', [
            'controller_name' => 'TabController',
            'tab'=>$tab
        ]);
    }
}
