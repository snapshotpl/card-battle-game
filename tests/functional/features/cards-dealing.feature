@cards-dealing
Feature: Dealing cards

  Scenario:
    Given There is "Player1" with 20 HP
    And There is "Player2" with 20 HP
    And game was created with 2 MP per turn with "Player1" on turn and "Player2" waiting
    When card of type "damage" with value "2" and cost of "1" MP is dealt for player on turn
    Then player on turn has on hand card of type "damage" with value "2" and cost of "1" MP