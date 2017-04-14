<?php

namespace RstGroup\PhpStudyGroup\CardBattleGame\Tests\Functional\Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Ramsey\Uuid\Uuid;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Card;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Event\CardDealtForPlayerOnTurn;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Game;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\MovePoints;
use Webmozart\Assert\Assert;

class CardsDealingContext implements Context
{
    /**
     * @var GameCreatingContext
     */
    private $gameCreatingContext;
    /**
     * @var EventSourcedContext
     */
    private $eventSourcedContext;

    /** @BeforeScenario */
    public function attachToApplicationContext(BeforeScenarioScope $scope)
    {
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();
        $this->gameCreatingContext = $environment->getContext(GameCreatingContext::class);
        $this->eventSourcedContext = $environment->getContext(EventSourcedContext::class);
    }

    /**
     * @Given :playerName was dealt with card of type :cardType with value :cardValue and cost of :cardCost MP
     */
    public function wasDealtWithCardOfTypeWithValueAndCostOfMp($playerName, $cardType, $cardValue, $cardCost)
    {
        $card = new Card($cardType, $cardValue, new MovePoints($cardCost));

        $event = new CardDealtForPlayerOnTurn($card);

        $this->eventSourcedContext->appendEvent($event);
    }

    /**
     * @When card of type :cardType with value :cardValue and cost of :cardCost MP is dealt for player on turn
     */
    public function cardOfTypeWithValueAndCostOfMpIsDealtForPlayerOnTurn($cardType, $cardValue, $cardCost)
    {
        $this->eventSourcedContext->setAggregateRoot(new Game(Uuid::uuid4()));
        $this->eventSourcedContext->initializeAggregateRootState();

        $this->eventSourcedContext->getAggregateRoot()->dealCardForPlayerOnTurn(
            new Card($cardType, $cardValue, $cardCost)
        );
    }

    /**
     * @Then player on turn has on hand card of type :type with value :value and cost of :cost MP
     */
    public function playerOnTurnHasOnHandCardOfTypeWithValueAndCostOfMp($type, $value, $cost)
    {
        $this->eventSourcedContext->aggregateRootShouldHaveEvent(CardDealtForPlayerOnTurn::class);

        /**
         * @var Game $game
         */
        $game = $this->eventSourcedContext->getAggregateRoot();

        $card = $game->getPlayerOnTurn()->getHand()[0];

        Assert::same($type, $card->getType());
        Assert::same((int) $value, $card->getValue());
        Assert::same((int) $cost, $card->getMpCost());
    }
}
