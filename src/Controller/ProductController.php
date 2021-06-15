<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Service\ImageUploader;
use App\Service\UserProdAndRole;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProductController extends AbstractController
{

    /**
     * @Route("/product/listAll/{page<\d+>?1}/{number<\d+>?6}", name="product.list.all")
     */
    public function index($page, $number, UserProdAndRole $userProdAndRole): Response
    {
        $user = $userProdAndRole->getUser();
        $nbProds = $userProdAndRole->getNbProds();
        $isAdmin = $userProdAndRole->isAdmin();


        $repository = $this->getDoctrine()->getRepository('App:Product');
        $conditions = [];
        $products = $repository->findBy($conditions, ['productPrice'=> 'asc'],$number, ($page - 1) * $number);
        return $this->render('product/index.html.twig', [
            'products' => $products,
            'isAdmin' => $isAdmin,
            'user' => $user,
            'nbProds' => $nbProds
        ]);
    }




    /**
     * @Route("/product/add", name="product.add")
     */
    public function addProduct(EntityManagerInterface $manager, Request  $request ,ImageUploader $imageUploader, UserProdAndRole $userProdAndRole) {


        $user = $userProdAndRole->getUser();
        $nbProds = $userProdAndRole->getNbProds();
        $isAdmin = $userProdAndRole->isAdmin();

        if($isAdmin == false){
            $this->addFlash('error','You are not authorized!');
            return $this->redirectToRoute("product.list.all");
        }
        $product = new Product() ;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $file = $form['productPhoto']->getData();


            $fileDestination = $imageUploader->upload($file) ;
            $product->setProductPhoto($fileDestination) ;

            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', "product ".$product->getProductName()." successfully added");
            return $this->redirectToRoute('product.list.all');
        }
        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'isAdmin' => $isAdmin,
            'nbProds' => $nbProds
        ]);
    }

    /**
     * @Route("/product/delete/{product}", name="product.delete")
     */
    public function deleteProduct(EntityManagerInterface $manager, Product $product = null, UserProdAndRole $userProdAndRole ) {


        $user = $userProdAndRole->getUser();
        $nbProds = $userProdAndRole->getNbProds();
        $isAdmin = $userProdAndRole->isAdmin();

        if($isAdmin == false){
            $this->addFlash("error","You are not authorized");
            return $this->redirectToRoute("product.list.all");
        }
        if ($product) {
            $productName = $product->getProductName();
            $manager->remove($product);
//        $manager->persist($product2);
            $manager->flush();
            $this->addFlash('success', "le produit $productName  a été supprimé avec succès");
        } else {
            $this->addFlash('error', "le produit  est innexistant");
        }

        return $this->redirectToRoute('product.list.all');
    }

    /**
     * @Route("/product/list/{id}", name="product.list.category")
     */
    public function listProd(Category $id,UserProdAndRole $userProdAndRole, EntityManagerInterface $em){
        $user = $userProdAndRole->getUser();
        $nbProds = $userProdAndRole->getNbProds();
        $isAdmin = $userProdAndRole->isAdmin();
        $repo = $em->getRepository("App:Product");
        $prods = $repo->findBy(['category' => $id]);

        return $this->render('product/index.html.twig',[
            'products' => $prods,
            'isAdmin' => $isAdmin,
            'nbProds' => $nbProds,
            'user' => $user

        ]);
    }


    /**
     * @Route("/product/edit/{product}", name="product.edit")
     */
    public function editProduct(EntityManagerInterface $manager,Request  $request , Product $product  ,ImageUploader $imageUploader, UserProdAndRole $userProdAndRole ) {


        $user = $userProdAndRole->getUser();
        $isAdmin = $userProdAndRole->isAdmin();
        $nbProds = $userProdAndRole->getNbProds();

        if($isAdmin == false){
            $this->addFlash("error","You are not authorized");
            return $this->redirectToRoute("product.list.all");
        }

        $form = $this->createForm(ProductType::class, $product) ;
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $file = $form['productPhoto']->getData();


            $fileDestination = $imageUploader->upload($file) ;
            $product->setProductPhoto($fileDestination) ;

            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', "product ".$product->getProductName()." successfully modified");
            return $this->redirectToRoute('product.list.all');
        }
        return $this->render('product/add.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'isAdmin' => $isAdmin,
            'nbProds' => $nbProds
        ]);
    }


    /**
     * @Route("/product/{id}", name="product.detail")
     */
    public function detailProduct(Product $id = null, UserProdAndRole $userProdAndRole) {
        $user = $userProdAndRole->getUser();
        $isAdmin = $userProdAndRole->isAdmin();
        $nbProds = $userProdAndRole->getNbProds();
        return $this->render('product/detail.html.twig', [
            'p' => $id,
            'user' => $user,
            'isAdmin' => $isAdmin,
            'nbProds' => $nbProds
        ]);
    }

}
