<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->getConnection()->executeStatement(
            'INSERT INTO accounts (balance) VALUES (0.00)'
        );

        $manager->flush();
    }
}
