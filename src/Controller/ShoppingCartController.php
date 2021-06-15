<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\ShoppingCart;

class ShoppingCartController extends AbstractController
{
    /**
     *
     * @Route("/cart",name="cart")
     */
    public function index(EntityManagerInterface $manager, Security $security): Response
    {
        $user = $security->getUser();

        if($user){
            $repository = $this->getDoctrine()->getRepository(ShoppingCart::class);
            $cart = $repository->findOneBy([
                'user' => $user->getId() ,
            ]);

            if($cart){
                $itemRepository = $this->getDoctrine()->getRepository(CartItem::class);
                $items = $itemRepository->findBy(
                    ['shoppingCart' => $cart ]
                );
                return $this->render('shopping_cart/index.html.twig', [
                    'items' => $items ,
                ]);

            }else{
                $newCart = new ShoppingCart() ;
                $newCart->setUser($user) ;
                $newCart->setCreatedAt() ;
                $manager->persist($newCart);
                $manager->flush();

                return $this->render('shopping_cart/index.html.twig', [
                    'items' => null ,
                ]);
            }

        }else{
            //add flashbag
            $this->addFlash('error', "you must be logged in");
            //redirect lo login
            return $this->redirectToRoute('app_login');
        }
    }


    /**
     *
     * @Route("/cart/add/{product}/{qte}",name="cart.add")
     */
    public function add(Product $product , $qte ,EntityManagerInterface $manager, Security $security): Response
    {
        $user = $security->getUser();

        if($user){
            $repository = $this->getDoctrine()->getRepository(ShoppingCart::class);
            $cart = $repository->findOneBy([
                'user' => $user->getId() ,
            ]);

            if($cart){
                //add to line
                $cartItem = new CartItem() ;
                $cartItem->setAddedat() ;
                $cartItem->setCartItemQte($qte) ;
                $cartItem->setProduct($product) ;
                $cartItem->setShoppingCart($cart) ;
                $manager->persist($cartItem);
                $manager->flush();
                return $this->redirectToRoute('cart');


            }else{
                $newCart = new ShoppingCart() ;
                $newCart->setUser($user) ;
                $newCart->setCreatedAt() ;
                $manager->persist($newCart);
                $manager->flush();


                $cartItem = new CartItem() ;
                $cartItem->setAddedat() ;
                $cartItem->setCartItemQte($qte) ;
                $cartItem->setProduct($product) ;
                $cartItem->setShoppingCart($newCart) ;
                $manager->persist($cartItem);
                $manager->flush();
                return $this->redirectToRoute('cart');
            }

        }else{
            //add flashbag
            $this->addFlash('error', "you must be logged in");
            //redirect lo login
            return $this->redirectToRoute('app_login');
        }
    }



    /**
     *
     * @Route("/cart/delete/{item}",name="cart.delete")
     */
    public function delete(CartItem $item = null  ,EntityManagerInterface $manager, Security $security): Response
    {
        if ($item) {
            //$productName = $product->getProductName();
            $manager->remove($item);
//        $manager->persist($product2);
            $manager->flush();
            $this->addFlash('success', "the cart item has been remove");
        } else {
            $this->addFlash('error', "the cart item does not exist");
        }

        return $this->redirectToRoute('cart');
    }
}
