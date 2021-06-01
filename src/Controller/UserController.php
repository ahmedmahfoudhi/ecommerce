<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
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
            return $this->render('user/homehome.html.twig', [
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
            $session->clear();
            if($user != null) {
                $user->setDeletedAt(new \DateTime("now"));
                $doctrine->flush();
                $this->addFlash('success', "Your account has been deleted successfully");
                return $this->render("home.html.twig");
            }
        }
        $this->addFlash("error","You are not logged in!");
        return $this->render("home.html.twig");
    }

    /**
     * @Route("/login", name="user.login")
     */

    public function login(SessionInterface $session, EntityManagerInterface $doctrine): Response{
        // TODO : get information from the form ans assign it to the $form variable
        if($session->has('id')){
            $this->addFlash("error","You are already logged in!");
            return $this->render("user/home.html.twig");
        }
        $userRepo = $doctrine->getRepository(UserRepository::class);
        $info = $form->get();
        $user = $userRepo->findOneBy(['email' => $info['email']]);
        if($user == null){
            $this->addFlash('error',"Wrong Email!");
            return $this->render("user/login.html.twig");
        }
        if($user->getPassword() != $info['password']){
            $this->addFlash('error',"Wrong Password!");
            return $this->render("user/login.html.twig");
        }
        if($user->getDeletedAt() != null){
            $this->addFlash('error',"Account has been deleted at $user->getDeletedAt(), Unfortunately you have to register a new account!");
            return $this->render("user/register.html.twig");
        }


        $session->set('id',$user->getId());
        $this->addFlash("info","Welcome back!");
        return $this->redirectToRoute("user");
    }

    /**
     * @Route("/logout", name="user.logout")
     */

    public function logout(SessionInterface $session): Response{
        if(!$session->has('id')){
            $this->addFlash("error","You are not logged in!");
            return $this->render("user/login.html.twig");
        }
        $session->clear();
        return $this->render("home.html.twig");
    }

    /**
     * @Route("/update", name="user.update")
     */

    public function update(SessionInterface $session, EntityManagerInterface $doctrine): Response{
        if(!$session->has('id')){
            $this->addFlash("error","You are not logged in!");
            return $this->render("user/login.html.twig");
        }
        $userRepo = $doctrine->getRepository(UserRepository::class);
        $user = $userRepo->find($session->get('id'));
        if($user == null){
            // this part is added to check if the actual id is valid and not played out by someone! (security issues)
            $session->clear();
            $this->addFlash("error","You are not logged in!");
            return $this->render("user/login.html.twig");
        }
        // $info contains the information to be updated
        $user->setFirstname($info['firstname']);
        $user->setLastname($info['lastname']);
        $user->setState($info['state']);
        $user->setStreet($info['street']);
        $user->setPostalCode($info['postalCode']);
        $user->setPhoneNumber($info['phoneNumber']);
        $user->setFirstname($info['firstname']);
        // in order to change his email or password he has to re-enter his password
        $doctrine->flush();

    }



}
