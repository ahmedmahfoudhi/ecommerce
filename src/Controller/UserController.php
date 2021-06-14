<?php

namespace App\Controller;

use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\ImageUploader;
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
    public function index(Security $security): Response
    {
        // ToDo add user data list
        $user = $security->getUser();
        $this->addFlash('error',"You are not logged in!");
        return $this->render("user/home.html.twig");
    }


    /**
     * @Route("/delete/{user}", name="user.delete")
     */
    // ToDo add delete user and add confirmation
    public function deleteUser(Security $security,EntityManagerInterface $manager, User $user , Request $request ): Response
    {

        $loggedUser = $security->getUser();

        if($loggedUser){
            if(in_array("ROLE_ADMIN", $loggedUser->getRoles())){
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

    /*

    /**
     * @Route("/login", name="user.login")
     */

    /*

    public function login(SessionInterface $session, EntityManagerInterface $doctrine): Response{
        // TODO : get information from the form ans assign it to the $form variable
        if($session->has('id')){
            $this->addFlash("error","You are already logged in!");
            return $this->render("user/home.html.twig");
        }
        $info = Request::createFromGlobals()->request;

        $userRepo = $doctrine->getRepository(UserRepository::class);
        $user = $userRepo->findOneBy(['email' => $info->get('email')]);
        if($user == null){
            $this->addFlash('error',"Wrong Email!");
            return $this->render("user/login.html.twig");
        }
        if($user->getPassword() != $info->get('password')){
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

    /*

    public function logout(SessionInterface $session): Response{
        if(!$session->has('id')){
            $this->addFlash("error","You are not logged in!");
            return $this->render("user/login.html.twig");
        }
        $session->clear();
        return $this->render("home.html.twig");
    }
    */




    /**
     * @Route("/edit/{user}", name="user.edit")
     */

    public function edit(Security $security, User $user , Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, ImageUploader $imageUploader): Response
    {

        $loggedUser = $security->getUser();

        if($loggedUser){

            if(in_array("ROLE_ADMIN", $loggedUser->getRoles())){
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

                    return $guardHandler->authenticateUserAndHandleSuccess(
                        $user,
                        $request,
                        $authenticator,
                        'main' // firewall name in security.yaml
                    );
                }

                return $this->render('user/edit.html.twig', [
                    'editUserForm' => $form->createView(),
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

    public function editSelf(Security $security, Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, ImageUploader $imageUploader): Response
    {
        $user = $security->getUser();

        if($user){
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

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }

            return $this->render('user/edit.html.twig', [
                'editUserForm' => $form->createView(),
            ]);
        }else{
            //add flashbag
            $this->addFlash('error', "you must be logged in");
            //redirect lo login
            return $this->redirectToRoute('app_login');
        }

    }


}
