<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'categories')]
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
     * @Route("/category/add/{name}",name="category.add")
     */
    public function addCategory($name , EntityManagerInterface $manager): Response
    {
        // ToDo add prePersiste for the created at
        //ToDo complete form submit
        $category = new Category() ;
//        $category -> setCategoryName($name) ;
//        $category -> setCreatedAt(new \DateTime()) ;
//        $manager-> persist($category);
//        $manager->flush();
        $form = $this->createForm(CategoryType::class,$category) ;

        return $this->render('category/category.html.twig', [
            //'category' => $category,
            'form' => $form->createView() ,
        ]);
       // return $this->redirectToRoute('categories') ;

    }

    /**
     *
     * @Route("/category/delete/{id}",name="category.delete")
     */
    public function deleteCategory($id , EntityManagerInterface $manager):Response {
        $repository = $this->getDoctrine()->getRepository(Category::class);
        $categoryToDelete = $repository->findOneBy(['categoryId' => $id]);
        if(!$categoryToDelete){
            // ToDo  add flash bag message
        }else{
            $categoryToDelete -> setDeletedAt(new \DateTime())  ;
            $manager-> persist($categoryToDelete);
            $manager->flush();
        }
        return $this->redirectToRoute('categories') ;

    }


}
