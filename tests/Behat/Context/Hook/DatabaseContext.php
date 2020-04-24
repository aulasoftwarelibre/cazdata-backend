<?php


namespace App\Tests\Behat\Context\Hook;


use Behat\Behat\Context\Context;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;

final class DatabaseContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @BeforeScenario
     */
    public function purgeDatabase()
    {
        $this->manager->getConnection()->getConfiguration()->setSQLLogger(null);
        $purger = new ORMPurger($this->manager);
        $purger->purge();
        $this->manager->clear();
    }
}
