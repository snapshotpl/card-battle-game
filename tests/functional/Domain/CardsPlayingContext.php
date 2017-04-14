<?php

namespace RstGroup\PhpStudyGroup\CardBattleGame\Tests\Functional\Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Ramsey\Uuid\Uuid;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Card;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Event\CardPlayed;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Game;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\MovePoints;
use Webmozart\Assert\Assert;

class CardsPlayingContext implements Context
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
     * @When :playerName plays the card of type :cardType with value :cardValue and cost of :cardCost MP
     */
    public function playsTheCardOfTypeWithValueAndCostOfMp($playerName, $cardType, $cardValue, $cardCost)
    {
        $this->eventSourcedContext->setAggregateRoot(new Game(Uuid::uuid4()));
        /* @var $game Game */
        $game = $this->eventSourcedContext->initializeAggregateRootState();

        $card = new Card($cardType, $cardValue, new MovePoints($cardCost));
        $player = $this->gameCreatingContext->getPlayers()[$playerName];

        $game->play($card, $player);
    }

    /**
     * @Then card of type :cardType with value :cardValue and cost of :cardCost MP was played
     */
    public function cardOfTypeWithValueAndCostOfMpWasPlayed($cardType, $cardValue, $cardCost)
    {
        /* @var $event CardPlayed */
        $event = $this->eventSourcedContext->aggregateRootShouldHaveEvent(CardPlayed::class);

        $card = $event->getCard();

        Assert::same((string) $cardType, $card->getType());
        Assert::same((int) $cardValue, $card->getValue());
        Assert::same((int) $cardCost, $card->getMpCost()->getPointsCount());
    }

}