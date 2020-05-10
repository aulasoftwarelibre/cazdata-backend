<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Setup;

use App\Entity\Animal;
use App\Tests\Behat\Services\SharedStorage;
use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

final class AnimalContext implements Context
{
    private EntityManagerInterface $manager;
    private SharedStorage $sharedStorage;

    public function __construct(EntityManagerInterface $manager, SharedStorage $sharedStorage)
    {
        $this->manager       = $manager;
        $this->sharedStorage = $sharedStorage;
    }

    /**
     * @Given /^there are registered the species "([^"]*)" that belongs to "([^"]*)" hunting$/
     */
    public function thereAreRegisteredTheSpeciesThatBelongsToHunting(string $name, string $type) : void
    {
        $imageFile = new File(__DIR__ . '/../../Resources/fixtures/image.gif');

        $animal = new Animal();
        $animal->setName($name);
        $animal->setType($type === 'varmint' ? Animal::TYPES[0] : Animal::TYPES[1]);
        $animal->setIsEnabled(true);
        $animal->setImageFile($imageFile);
        $animal->setImageName($name . '.png');

        $this->manager->persist($animal);
        $this->manager->flush();

        $this->sharedStorage->set('animal', $animal);
    }
}
