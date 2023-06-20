<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TabController extends AbstractController
{
    #[Route('/tab/lol/{nb?5}', name: 'app_tab')]
    public function index($nb): Response
    {
        $tab=[];
        for($i=0;$i<$nb;$i++){
            $tab[$i]=rand(1,4);
        }
        return $this->render('tab/index.html.twig', [
            'controller_name' => 'TabController',
            'tab'=>$tab
        ]);
    }
    #[Route('/tab/user', name: 'user')]
    public function user(): Response
    {
        $user=[
            ['firstname'=>'celian', 'age'=>'20', 'name'=>'laumond'],
            ['firstname'=>'andrea', 'age'=>'20', 'name'=>'lajou'],
            ['firstname'=>'matthieu', 'age'=>'19', 'name'=>'ruans'],
            ['firstname'=>'nathan', 'age'=>'20', 'name'=>'chaize'],
        ];
        return $this->render('tab/users.html.twig',[
            'users'=>$user
        ]);
    }
}
