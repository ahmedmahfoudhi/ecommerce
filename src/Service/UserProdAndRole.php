<?php


namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Security\Core\Security;

class UserProdAndRole
{
    private $security;
    private $em;
    private $isAdmin;
    private $user;


    public function getUser()
    {
        return $this->user;
    }
    public function __construct(Security $security,EntityManagerInterface $em){
        $this->security = $security;
        $this->user = $security->getUser();
        $this->isAdmin = false;
        if($this->user != null){
            if(in_array('ROLE_ADMIN',$this->user->getRoles())){
                $this->isAdmin = true;
            }
        }

        $this->em = $em;
    }

    public function getNbProds(){
        if($this->user == null){
            return 0;
        }
        $shopping = $this->em->getRepository("App:ShoppingCart");
        $nbProds = $shopping->findBy(['user'=> $this->user->getId()]);
        return count($nbProds);

    }

    public function isAdmin(){
        return $this->isAdmin;
    }
}