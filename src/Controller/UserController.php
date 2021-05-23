<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */

class UserController extends AbstractController
{
    /**
     * @Route("/", name="user")
     */
    public function index(SessionInterface $session): Response
    {
        if($session->has('id')){
            return $this->render('user/home.html.twig', [
                'controller_name' => 'UserController',
            ]);
        }
        $this->addFlash('error',"You are not logged in!");
        return $this->render("user/login.html.twig");
    }




    /**
     * @Route("/deleteuser", name="user.delete")
     */

    public function deleteUser(SessionInterface $session, EntityManagerInterface $doctrine) : Response{
        if($session->has('id')){
            $userRepo = $doctrine->getRepository(UserRepository::class);
            $user = $userRepo->find($session->get('id'));
            unset($session);
            if($user != null) {
                $doctrine->remove($user);
                $doctrine->flush();
                $this->addFlash('success', "Your account has been deleted successfully");
                return $this->render("home.html.twig");
            }
        }
        $this->addFlash("error","You are not logged in!");
        return $this->render("home.html.twig");
    }
}
