<?php

namespace App\DataFixtures;

use App\Entity\Platform;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 100; ++$i) {
            $platform = new Platform();
            $platform->setName(sprintf('Platform %d', $i));
            $platform->setDescription(sprintf('Description de la platform %d', $i));

            $manager->persist($platform);
        }

        $manager->flush();
    }
}
