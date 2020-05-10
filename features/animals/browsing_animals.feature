@animals @api
Feature: Browsing animals
    In order to configure a journey
    As a hunter
    I want to browse all available animals

    Background:
        Given there are registered the species "Rabbit" that belongs to "varmint" hunting
        And there are registered the species "Lion" that belongs to "big-game" hunting

    Scenario: Browsing animals
        When I browse the animals
        Then I should see 2 animals in the list
        And the animal "Rabbit" should be in the list
        And the animal "Lion" should be in the list

