<?php
/**
 * @file
 * DataFixtureContext.php
 */

namespace Tests\Functional\Context\Web;

use AppBundle\DataFixtures\ORM\LoadFixtures;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bridge\Doctrine\DataFixtures\ContainerAwareLoader;

/**
 * Class DataFixtureContext
 *
 * @package Tests\Functional\Context\Web
 */
class DatabaseContext extends BaseWebContext
{
    /**
     * Purges the database prior to executing a scenario tagged with
     * @cleanDatabase
     *
     * @BeforeScenario @cleanDatabase
     */
    public function cleanDatabase()
    {
        /* @var \Doctrine\ORM\EntityManager $em */
        $em = $this->getContainer()->get('doctrine')
            ->getManager();

        $purger = new ORMPurger($em);
        $purger->purge();
    }

    /**
     * Loads data fixtures prior to a scenario (tagged with @fixtures) being
     * run.
     *
     * @BeforeScenario @loadFixtures
     */
    public function loadFixtures()
    {
        $loader = new ContainerAwareLoader($this->getContainer());
        $loader->addFixture(new LoadFixtures());

        $executor = new ORMExecutor($this->getEntityManager());
        $executor->execute($loader->getFixtures(), true);
    }
}
