<?php
/**
 * @file
 * ProductContext.php
 */

namespace Tests\Functional\Context\Web;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Tests\Assert\Assert;

/**
 * Class ProductContext
 */
class ProductContext extends BaseWebContext
{
    /**
     * Holds the user context class
     *
     * @var UserContext
     */
    protected $userContext;

    /**
     * @BeforeScenario
     *
     * @param \Behat\Behat\Hook\Scope\BeforeScenarioScope $scope
     */
    public function gatherContexts(BeforeScenarioScope $scope)
    {
        /* @var \Behat\Behat\Context\Environment\InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();
        $this->userContext = $environment->getContext(UserContext::class);
    }

    /**
     * @Given there are/is :count product(s)
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function thereAreProducts($count)
    {
        $this->createProducts($count);
    }

    /**
     * @Given I author :count products
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function iAuthorProducts($count)
    {
        $this->createProducts($count, $this->userContext->getCurrentUser());
    }

    /**
     * @Given the following products exist
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function theFollowingProductsExist(TableNode $table)
    {
        $em = $this->getEntityManager();

        foreach ($table as $row) {
            $product = $this->instantiateRandomProduct();
            if (!empty($row['name'])) {
                $product->setName($row['name']);
            }

            if (!empty($row['is published'])){
                $product->setIsPublished(
                    ($row['is published'] !== 'no')
                );
            }

            $em->persist($product);
        }

        $em->flush();
    }


    /**
     * @Then I should see :count products
     */
    public function iShouldSeeProducts($count)
    {
        $table = $this->getPage()->find('css', 'table.table');

        Assert::assertNotNull($table, 'Could not find table');

        // Must cast here as all step definitions receive string args
        Assert::assertCount((int) $count, $table->findAll('css', 'tbody tr'));
    }

    /**
     * Create a number of products optionally setting their author
     *
     * @param int $count
     * @param \AppBundle\Entity\User|null $author
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createProducts($count, User $author = null)
    {
        $em = $this->getEntityManager();
        for ($i = 0; $i < $count; $i++) {
            $product = $this->instantiateRandomProduct();
            $product->setName('Product '.$i);
            if ($author !== null) {
                $product->setAuthor($author);
            }

            $em->persist($product);
        }

        $em->flush();
    }

    /**
     * Instantiates a random product entity
     *
     * @return \AppBundle\Entity\Product
     */
    private function instantiateRandomProduct()
    {
        $product = new Product();
        $product->setName('Random Product');
        $product->setPrice(mt_rand(10, 100));
        $product->setDescription('lorem');
        $product->setIsPublished(true);

        return $product;
    }

}
