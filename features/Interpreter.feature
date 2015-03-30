Feature: Interpreter
    In order to test and demonstrate my programs
    As a programmer
    I need to be able to run my programs

    Scenario: Hello, world!
        Given I have a hello_world example
        When I run it
        Then I should see "Hello, world!"

    Scenario: echo
        Given I have an echo example
        When I run it with "test"
        Then I should see "test"

    Scenario: sort
        Given I have a sort example
        When I run it with "features/examples/languages.txt"
        Then I should see the following:
        """
        Basic
        C#
        CSS
        HTML
        Java
        JavaScript
        PHP
        """

