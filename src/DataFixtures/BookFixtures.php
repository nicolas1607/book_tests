<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Author;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BookFixtures extends Fixture
{
    const FAKE_BOOKS = [
        ['Dans l\'abîme du temps', '1936']
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::FAKE_BOOKS as $fakeBook) {
            $book = new Book;
            $book->setName($fakeBook[0])
                ->setReleaseAt(new \DateTimeImmutable($fakeBook[1]));
            // ->setAuthor($manager->getRepository(Author::class)->find(2));
            $manager->persist($book);
        }
        $manager->flush();
    }
}
