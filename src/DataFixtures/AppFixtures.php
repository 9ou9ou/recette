<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{   
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $user= new User();
        $user->setUsername('koussay');
        $user->setEmail('koussayjebari2070@gmail.com');
        $user->setPassword($this->encoder->encodePassword($user, '1111'));
        $manager->persist($user);

        $manager->flush();
    }
}
