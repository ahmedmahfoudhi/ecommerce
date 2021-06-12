<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
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
     * @Route("/list/{page<\d+>?1}/{number<\d+>?6}", name="product.list")
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
    public function addProduct(EntityManagerInterface $manager, Request  $request, SluggerInterface $slugger) {

        $product = new Product() ;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted()) {

            $file = $form['productPhoto']->getData();
            $someNewFilename = $file->getClientOriginalName();;
            $product->setProductPhoto($someNewFilename);

            try {
                $file->move(
                    $this->getParameter('products_directory'),
                    $someNewFilename
                );
            } catch (FileException $e) {}


            $manager->persist($product);
            $manager->flush();
            $this->addFlash('success', "product ".$product->getProductName()." successfully added");
            return $this->redirectToRoute('product.list');
        }
        return $this->render('product/add.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
