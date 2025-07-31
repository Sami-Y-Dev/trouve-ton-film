<?php

namespace App\DataFixtures;

use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Movie;

class AvisFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 30; $i++) {
            $avis = new Avis();
            $avis->setNote($faker->numberBetween(1, 5));
            $avis->setContent($faker->paragraph());
            $avis->setDateAvis($faker->dateTimeBetween('-1 years', 'now'));  // <-- ici

            $user = $this->getReference('user_' . $faker->numberBetween(0, 4), User::class);
            $movie = $this->getReference('movie_' . $faker->numberBetween(0, 4), Movie::class);

            $avis->setUser($user);
            $avis->setMovie($movie);

            $manager->persist($avis);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            MovieFixtures::class,
        ];
    }
}
