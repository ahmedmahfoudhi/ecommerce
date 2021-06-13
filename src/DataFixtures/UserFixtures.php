<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{

    private $passwordEncoder ;
    /**
     * UserFixtures constructor.
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->passwordEncoder = $encoder ;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User() ;
        $user->setEmail('user@gmail.com');
        $user->setFirstname('user') ;
        $user->setState('userState') ;
        $user->setPhoneNumber(22000000);
        $user->setStreet('userStreet') ;
        $user->setPostalCode(3333);
        $user->setLastname('user') ;
        $user->setPassword(
            $this->passwordEncoder->encodePassword($user,'user')
        );

        $admin = new User() ;
        $admin->setEmail('admin@gmail.com');

        $admin->setFirstname('admin') ;
        $admin->setLastname('admin') ;
        $admin->setState('userState') ;
        $admin->setPhoneNumber(22000000);
        $admin->setStreet('userStreet') ;
        $admin->setPostalCode(3333);

        $admin->setRoles(['ROLE_ADMIN']) ;
        $admin->setPassword(
            $this->passwordEncoder->encodePassword($admin,'admin')
        );

        $manager->persist($user);
        $manager->persist($admin);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['groupUser'] ;
    }
}
