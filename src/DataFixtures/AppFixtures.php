<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $user = new User();

      $user->setEmail('kyle@test.com');
      $user->setFirstName('Kyle');
      $user->setLastName('Tong');
      $user->setPassword('123test123');

      $manager->persist($user);

      $manager->flush();
    }
}
