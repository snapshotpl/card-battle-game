@cards-playing
Feature: Playing cards

  Scenario: Damage card can be played
    Given There is "Player1" with 20 HP
    And There is "Player2" with 20 HP
    And game was created with 2 MP per turn with "Player1" on turn and "Player2" waiting
    And "Player1" was dealt with card of type "damage" with value "2" and cost of "1" MP
    When "Player1" plays the card of type "damage" with value "2" and cost of "1" MP
    Then card of type "damage" with value "2" and cost of "1" MP was played
    And "Player2" will have 18 HP left
    And Turn has "1" MP left
