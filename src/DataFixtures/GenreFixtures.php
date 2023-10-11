<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class GenreFixtures extends Fixture
{
    private const GENRES = [
        'Comedy',
        'Famille',
        'Biopic',
        'Drame',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::GENRES as $genreName) {
            $genre = (new Genre())
                ->setName($genreName)
            ;

            $manager->persist($genre);
        }

        $manager->flush();
    }
}
