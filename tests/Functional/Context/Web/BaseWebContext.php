<?php
/**
 * @file
 * BaseKernelContext.php
 */

namespace Tests\Functional\Context\Web;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

/**
 * Class BaseKernelContext
 *
 * Contains sim
 */
abstract class BaseWebContext extends RawMinkContext implements KernelAwareContext
{
    /**
     * Leverage symfony bootstrapping.
     *
     * Note: this trait wont trigger initialisation of the context due to the
     * abstract/extended nature of this class. Ie, classes extending this one
     * wont automatically be initialised as the reflection check provided by
     * the symfony extension during context initialise doesnt't find this trait.
     * Therefore to force it through make sure the class, implements the
     * KernelAwareContext interfce
     */
    use KernelDictionary;

    /**
     * Returns the current page
     *
     * @return \Behat\Mink\Element\DocumentElement
     */
    protected function getPage()
    {
        return $this->getSession()->getPage();
    }

    /**
     * Returns the entity manager object
     *
     * @return \Doctrine\ORM\EntityManager|object
     */
    protected function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}
