<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MovieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $movie = new Movie();
            $movie->setTitle($faker->sentence());
            $movie->setSynopsis($faker->paragraph());
            $movie->setDateSortie($faker->dateTimeBetween('-10 years', 'now'));

            $genre = $this->getReference('genre_' . $faker->numberBetween(0, 4), Genre::class);
            $movie->setGenre($genre);

            $manager->persist($movie);

            $this->addReference('movie_' . $i, $movie);
        }

        $manager->flush();


    }

    public function getDependencies(): array
    {
        return [
            GenreFixtures::class,
        ];
    }
}
