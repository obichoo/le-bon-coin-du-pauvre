<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Factory\AdFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        AdFactory::createMany(10);
        UserFactory::createMany(10);

    }
}
