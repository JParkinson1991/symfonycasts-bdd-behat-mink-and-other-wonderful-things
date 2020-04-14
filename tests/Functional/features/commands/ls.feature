Feature: ls
    In order to see a directory structure
    As a UNIX user
    I need to be able to list the directory contents

    # Applied to all of the scenarios
    Background:
        Given there is a file named "john"

    Scenario: List 2 files in a directory
        Given there is a file named "hammond"
        When I run "ls"
        Then I should see "john" in the output
        And I should see "hammond" in the output

    Scenario: List 1 file and 1 directory
        Given there is a dir named "ingen"
        When I run "ls"
        Then I should see "john" in the output
        And I should see "ingen" in the output
