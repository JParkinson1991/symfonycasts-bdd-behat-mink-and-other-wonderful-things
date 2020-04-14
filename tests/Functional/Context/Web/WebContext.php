<?php

namespace Tests\Functional\Context\Web;

use Tests\Assert\Assert;

/**
 * Defines application features from the specific context.
 *
 * An object of this class is created for every defined scenario automatically
 */
class WebContext extends BaseWebContext
{

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
     * @When I fill in the search box with :searchTerm
     */
    public function iFillInTheSearchBoxWith($searchTerm)
    {
        $searchInput = $this->getPage()
            ->find('css', '[name="searchTerm"]');

        // Ensure found
        Assert::assertNotNull($searchInput, 'Failed to find search input');

        $searchInput->setValue($searchTerm);
    }

    /**
     * @When I press the search button
     */
    public function iPressTheSearchButton()
    {
        $searchButton = $this->getPage()
            ->find('css', '#search_submit');

        Assert::assertNotNull($searchButton, 'Failed to find the search button');

        $searchButton->press();
    }

    /**
     * @When I click :linkText
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iClick($linkText)
    {
        $this->getPage()->clickLink($linkText);
    }

    /**
     * @When I wait for the modal to load
     */
    public function iWaitForTheModalToLoad()
    {
        // Waits for a modal to become visible for a maximum of 5 seconds
        $this->getSession()->wait(
            5000,
            "$('.modal:visible').length"
        );
    }

    /**
     * @Then the :rowText row should have a check icon
     */
    public function theRowShouldHaveACheckIcon($rowText)
    {
        $row = $this->findRowWithText($rowText);

        Assert::assertContains('fa-check', $row->getHtml(), 'Failed to find check for row with text: '.$rowText);

    }

    /**
     * @Then the :rowText row should have a cross icon
     */
    public function theRowShouldHaveACrossIcon($rowText)
    {
        $row = $this->findRowWithText($rowText);

        Assert::assertContains('fa-times', $row->getHtml(), 'Failed to find check for row with text: '.$rowText);
    }

    /**
     * @When I click :linkText in the :rowText row
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iClickInTheRow($linkText, $rowText)
    {
        // Find the row
        $row = $this->findRowWithText($rowText);

        // Find the link, ensure found
        $link = $row->clickLink($linkText);
    }

    /**
     * @When I press :buttonText in the :rowText row
     *
     * @throws \Behat\Mink\Exception\ElementNotFoundException
     */
    public function iPressButtonInTheRow($buttonText, $rowText)
    {
        // Find the row
        $row = $this->findRowWithText($rowText);

        // Find the button, ensure found
        $row->pressButton($buttonText);
    }

    /**
     * Finds a row with text in a table (with an optionally provided selector)
     *
     * @param string $rowText
     *     The text to identify the row by
     * @param string $tableSelector
     *     The table selector
     *     Useful to provided narrow selection if multiple tables exists on
     *     a page being tested
     *
     * @return \Behat\Mink\Element\NodeElement|mixed|null
     */
    private function findRowWithText($rowText, $tableSelector = 'table')
    {
        $row = $this->getPage()->find(
            'css',
            sprintf(
                '%s tr:contains("%s")',
                trim($tableSelector),
                $rowText
            )
        );

        Assert::assertNotNull($row, 'Failed to find row with text: '.$rowText);

        return $row;
    }

}
