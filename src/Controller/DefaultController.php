<?php

namespace App\Controller;

use App\Service\UserProdAndRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(UserProdAndRole $userProdAndRole): Response
    {
        $isAdmin = $userProdAndRole->isAdmin();
        $nbProds = $userProdAndRole->getNbProds();
        $user = $userProdAndRole->getUser();


        return $this->render('base.html.twig', [
            'controller_name' => 'DefaultController',
            'user' => $user,
            'isAdmin' => $isAdmin,
            'nbProds' => $nbProds
        ]);
    }
}
