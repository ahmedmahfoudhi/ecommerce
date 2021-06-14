<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class ProductController extends AbstractController
{

    /**
     * @Route("/product/list/{page<\d+>?1}/{number<\d+>?6}", name="product.list")
     */
    public function index($page, $number): Response
    {
        $repository = $this->getDoctrine()->getRepository('App:Product');
        $conditions = [];
        $products = $repository->findBy($conditions, ['productPrice'=> 'asc'],$number, ($page - 1) * $number);
        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/product/add", name="product.add")
     */
    public function addProduct(EntityManagerInterface $manager, Request  $request ,ImageUploader $imageUploader) {

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
            return $this->redirectToRoute('product.list');
        }
        return $this->render('product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{product}", name="product.delete")
     */
    public function deleteProduct(EntityManagerInterface $manager, Product $product = null ) {

        if ($product) {
            $productName = $product->getProductName();
            $manager->remove($product);
//        $manager->persist($product2);
            $manager->flush();
            $this->addFlash('success', "le produit $productName  a été supprimé avec succès");
        } else {
            $this->addFlash('error', "le produit  est innexistant");
        }

        return $this->redirectToRoute('product.list');
    }


    /**
     * @Route("/product/edit/{product}", name="product.edit")
     */
    public function editProduct(EntityManagerInterface $manager,Request  $request , Product $product  ,ImageUploader $imageUploader ) {

        $form = $this->createForm(ProductType::class, $product) ;
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $file = $form['productPhoto']->getData();


            $fileDestination = $imageUploader->upload($file) ;
            $product->setProductPhoto($fileDestination) ;

            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', "product ".$product->getProductName()." successfully modified");
            return $this->redirectToRoute('product.list');
        }
        return $this->render('product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/product/{product}", name="product.detail")
     */
    public function detailProduct(Product $product = null) {
        return $this->render('product/detail.html.twig', [
            'product' => $product
        ]);
    }

}
