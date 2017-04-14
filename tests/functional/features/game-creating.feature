@game-creating
Feature: Creating the game

  Scenario: Game can be created
    Given There is "Player1" with 20 HP
    And There is "Player2" with 20 HP
    When game is created with 2 MP per turn with "Player1" on turn and "Player2" waiting
    Then game should be created
