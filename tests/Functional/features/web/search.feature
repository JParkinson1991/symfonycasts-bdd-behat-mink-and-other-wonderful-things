Feature: Search
    In order to find products dinosaurs love
    As a web user
    I need to be able to search for products

    Background:
        Given I am on "/"

    # Try not to use css selectors/internals
    # Write scenarios from the perspective of the 'As a'
    # ie/ What can they see.
    #
    # If elements to be targeted do not have easily grabbable visibile text
    # ie/ no label for input, icon only submit button yada yada
    # Use the available selectors:
    #     name attribute
    #     id attribute
    #
    # Cardinal rule, never use selectors!
    # Use custom definitions to abstract away selectors.
    @cleanDatabase @loadFixtures
    Scenario Outline:
        #When I fill in "searchTerm" with "<term>" -- bad, uses selector
        When I fill in the search box with "<term>"
        #And I press "search_submit" -- bad, uses selector
        And I press the search button
        Then I should see "<result>"
        Examples:
            | term    | result            |
            | Samsung | Samsung Galaxy    |
            | Xbox    | No products found |
