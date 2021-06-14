<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category",name="categories")
     */
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categories = $repository->findAll();
        //dd($products);
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     *
     * @Route("/category/add",name="category.add")
     */
    public function addCategory(EntityManagerInterface $manager , Request $request): Response
    {

        $category = new Category() ;
        $form = $this->createForm(CategoryType::class,$category) ;
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $manager->persist($category);
            $manager->flush();
            $categoryName = $category->getCategoryName() ;
            $this->addFlash('success', "the category $categoryName has been added successfully");
            return $this->redirectToRoute('categories');
        }
        return $this->render('category/category.html.twig', [
            'form' => $form->createView() ,
        ]);

    }




    /**
     *
     * @Route("/category/edit/{category}",name="category.edit")
     */
    public function editCategory(EntityManagerInterface $manager , Request $request ,Category $category): Response
    {

        $form = $this->createForm(CategoryType::class,$category) ;
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $manager->persist($category);
            $manager->flush();
            $categoryName = $category->getCategoryName() ;
            $this->addFlash('success', "the category $categoryName has been edited successfully");
            return $this->redirectToRoute('categories');
        }
        return $this->render('category/category.html.twig', [
            'form' => $form->createView() ,
        ]);

    }


    /**
     *
     * @Route("/category/delete/{id}",name="category.delete")
     */
    public function deleteCategory($id , EntityManagerInterface $manager):Response {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categoryToDelete = $repository->findOneBy(['categoryId' => $id]);
        if(!$categoryToDelete){
            $this->addFlash('error', "this category does not exist");
        }else{
            $categoryToDelete -> setDeletedAt(new \DateTime())  ;
            $manager-> persist($categoryToDelete);
            $manager->flush();
            $this->addFlash('success', "the category $categoryToDelete has been deleted successfully");
        }
        return $this->redirectToRoute('categories') ;

    }


}