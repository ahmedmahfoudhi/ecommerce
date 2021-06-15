<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\ImageUploader;
use App\Service\UserProdAndRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;


/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user")
     */
    public function index(UserProdAndRole $userProdAndRole): Response
    {
        
        $user = $userProdAndRole->getUser();
        $isAdmin = $userProdAndRole->isAdmin();
        $nbProd = $userProdAndRole->getNbProds();
        if($user){
            return $this->render("user/home.html.twig", [
                'user' => $user,
                'isAdmin' => $isAdmin,
                'nbProds' => $nbProd
            ]);
        }else{

            $this->addFlash('error', "you must be logged in");
            //redirect lo login
            return $this->redirectToRoute('app_login');
        }

    }


    /**
     * @Route("/list", name="user.list")
     */

    public function list(UserProdAndRole $userProdAndRole): Response
    {


        $loggedUser = $userProdAndRole->getUser();
        $isAdmin = $userProdAndRole->isAdmin();
        $nbProds = $userProdAndRole->getNbProds();




        if($loggedUser){

            if($isAdmin == true){
                $repository = $this->getDoctrine()->getRepository(User::class);
                $users = $repository->findAll();


                return $this->render('user/list.html.twig', [
                    'users' => $users ,
                    'user' => $loggedUser,
                    'isAdmin' => $isAdmin,
                    'nbProds' => $nbProds
                ]);

            }else{
                //add flashbag
                $this->addFlash('error', "you are not authorised");
                //redirect lo login
                return $this->redirectToRoute('user');

            }

        }else{
            //must login
            //add flashbag
            $this->addFlash('error', "you must be logged in");
            //redirect lo login
            return $this->redirectToRoute('app_login');
        }

    }



    /**
     * @Route("/delete/{user}", name="user.delete")
     */
    public function deleteUser(User $user, UserProdAndRole $userProdAndRole, EntityManagerInterface $manager): Response
    {
        $loggedUser = $userProdAndRole->getUser();
        $isAdmin = $userProdAndRole->isAdmin();

        if($loggedUser){
            if($isAdmin == true){
                $user->setDeletedAt();
                $manager->persist($user);
                $manager->flush();

                //must login
                //add flash bag
                $this->addFlash('success', "user deleted successfully");
                //redirect lo login
                return $this->redirectToRoute('user');
            }else{
                //add flashbag
                $this->addFlash('error', "you are not authorised");
                //redirect lo login
                return $this->redirectToRoute('user');
            }

        }else{
            //must login
            //add flashbag
            $this->addFlash('error', "you must be logged in");
            //redirect lo login
            return $this->redirectToRoute('app_login');
        }

    }


    /**
     * @Route("/edit/{user}", name="user.edit")
     */

    public function edit(UserProdAndRole $userProdAndRole, User $user , Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, ImageUploader $imageUploader): Response
    {

        $loggedUser = $userProdAndRole->getUser();
        $isAdmin = $userProdAndRole->isAdmin();
        $nbProd = $userProdAndRole->getNbProds();

        if($loggedUser){

            if($isAdmin == true){
                $form = $this->createForm(RegistrationFormType::class, $user);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    // encode the plain password
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                    $file = $form->get("avatar")->getData();
                    $fileName = $imageUploader->upload($file);
                    $user->setAvatar($fileName);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                    // do anything else you need here, like send an email

                    return $this->redirectToRoute("user"); //changed here
                }

                return $this->render('user/edit.html.twig', [
                    'userEditForm' => $form->createView(),
                    'nbProds' => $nbProd,
                    'isAdmin' => $isAdmin,
                    'user' => $loggedUser
                ]);

            }else{
                //add flashbag
                $this->addFlash('error', "you are not authorised");
                //redirect lo login
                return $this->redirectToRoute('user');

            }

        }else{
            //must login
            //add flashbag
            $this->addFlash('error', "you must be logged in");
            //redirect lo login
            return $this->redirectToRoute('app_login');
        }

    }


    /**
     * @Route("/edit", name="user.edit.self")
     */

    public function editSelf(UserProdAndRole $userProdAndRole, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, ImageUploader $imageUploader): Response
    {
        $user = $userProdAndRole->getUser();
        $isAdmin = $userProdAndRole->isAdmin();
        $nbProd = $userProdAndRole->getNbProds();

        if($user != null){
            $form = $this->createForm(UserType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                // encode the plain password
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $file = $form->get("avatar")->getData();
                $fileName = $imageUploader->upload($file);
                $user->setAvatar($fileName);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email
                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }else{
                return $this->render('user/edit.html.twig', [
                    'userEditForm' => $form->createView(),
                    'isAdmin' => $isAdmin,
                    'user' => $user,
                    'nbProds' => $nbProd
                ]);
            }


        }else{
            //add flashbag
            $this->addFlash('error', "you must be logged in");
            //redirect lo login
            return $this->redirectToRoute('app_login');
        }

    }


}
