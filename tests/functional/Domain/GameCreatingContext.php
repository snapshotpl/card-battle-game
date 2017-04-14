<?php

namespace RstGroup\PhpStudyGroup\CardBattleGame\Tests\Functional\Domain;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Environment\InitializedContextEnvironment;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Behat\Tester\Exception\PendingException;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Event\GameCreated;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Game;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\MovePoints;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Player;
use Webmozart\Assert\Assert;

final class GameCreatingContext implements Context
{
    private $players = [];

    /**
     * @var EventSourcedContext
     */
    private $eventSourcedContext;

    /** @BeforeScenario */
    public function attachToApplicationContext(BeforeScenarioScope $scope)
    {
        /** @var InitializedContextEnvironment $environment */
        $environment = $scope->getEnvironment();
        $this->eventSourcedContext = $environment->getContext(EventSourcedContext::class);
    }

    /**
     * @Given There is :player with :hpCount HP
     */
    public function thereIsWithHp($player, $hpCount)
    {
        $this->players[$player] = new Player($player, (int) $hpCount);
    }

    /**
     * @Given game was created with :mpCount MP per turn with :player1 on turn and :player2 waiting
     */
    public function gameWasCreatedWithMpPerTurnWithOnTurnAndWaiting($mpCount, $player1, $player2)
    {
        $event = new GameCreated(
            $this->getPlayers()[$player1],
            $this->getPlayers()[$player2],
            new MovePoints($mpCount)
        );

        $this->eventSourcedContext->appendEvent($event);
    }

    /**
     * @When game is created with :mpCount MP per turn with :player1 on turn and :player2 waiting
     */
    public function gameIsCreatedWithOnTurnAndWithMpPerTurn($mpCount, $player1, $player2)
    {
        $game = Game::create($this->players[$player1], $this->players[$player2], new MovePoints($mpCount));

        $this->eventSourcedContext->setAggregateRoot($game);
    }

    /**
     * @Then game should be created
     */
    public function gameShouldBeCreated()
    {
        $this->eventSourcedContext->aggregateRootShouldHaveEvent(GameCreated::class);
    }

    /**
     * @return array
     */
    public function getPlayers(): array
    {
        return $this->players;
    }

    /**
     * @Then :player will have :hpCount HP left
     */
    public function willHaveHpLeft($player, $hpCount)
    {
        /* @var $game Game */
        $game = $this->eventSourcedContext->getAggregateRoot();

        Assert::same((int) $hpCount, $game->getPlayerWaiting()->getHitPoints());
    }

    /**
     * @Then Turn has :mpCount MP left
     */
    public function turnHasMpLeft($mpCount)
    {
        throw new PendingException();
    }
}