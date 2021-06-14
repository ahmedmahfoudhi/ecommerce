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
        
        $user = $security->getUser();
        if($user){
            return $this->render("user/home.html.twig", [
                'user' => $user
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

    public function list(Security $security): Response
    {

        $loggedUser = $security->getUser();

        if($loggedUser){

            if(in_array("ROLE_ADMIN", $loggedUser->getRoles())){
                $repository = $this->getDoctrine()->getRepository(User::class);
                $users = $repository->findAll();


                return $this->render('user/list.html.twig', [
                    'users' => $users ,
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
