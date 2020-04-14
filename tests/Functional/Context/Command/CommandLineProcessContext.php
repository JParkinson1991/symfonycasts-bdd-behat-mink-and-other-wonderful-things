<?php
/**
 * @file
 * CommandLineProcessContext.php
 */

namespace Tests\Functional\Context\Command;

use Behat\Behat\Context\Context;
use Tests\Assert\Assert;

/**
 * Class CommandLineProcessContext
 */
class CommandLineProcessContext implements Context
{
    /**
     * Contains shell output produced when using iRun
     *
     * @var string
     */
    private $shellOutput;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function moveIntoTestDir()
    {
        if (!mkdir('test') && !is_dir('test')) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', 'test'));
        }
        chdir('test');
    }

    /**
     * @AfterScenario
     */
    public function moveOutOfTestDir()
    {
        chdir('..');
        if (is_dir('test')) {
            system('rm -rf '.realpath('test'));
        }
    }


    /**
     * @Given there is a file named :filename
     */
    public function thereIsAFileNamed($filename)
    {
        touch($filename);
    }

    /**
     * @Given there is a dir named :dir
     */
    public function thereIsADirNamed($dir)
    {
        if (!mkdir($dir) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $dir));
        }
    }

    /**
     * @When I run :command
     */
    public function iRun($command)
    {
        $this->shellOutput = shell_exec($command);
    }

    /**
     * @Then I should see :string in the output
     *
     * @throws \Exception
     */
    public function iShouldSeeInTheOutput($string)
    {
        Assert::assertContains(
            $string,
            $this->shellOutput,
            sprintf(
                'Did not see %s in the output %s',
                $string,
                $this->shellOutput
            )
        );
    }
}
