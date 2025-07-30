<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $genres = ['Action', 'Comédie', 'Drame', 'Horreur', 'Science-fiction'];

        foreach ($genres as $index => $name) {
            $genre = new Genre();
            $genre->setName($name);
            $manager->persist($genre);

            $this->addReference('genre_' . $index, $genre);
        }

        $manager->flush();
    }
}
