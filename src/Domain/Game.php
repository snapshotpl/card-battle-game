<?php

declare(strict_types=1);

namespace RstGroup\PhpStudyGroup\CardBattleGame\Domain;

use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Event\CardDealtForPlayerOnTurn;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Event\GameCreated;

final class Game extends EventSourcedAggregateRoot
{
    /**
     * @var Uuid
     */
    private $gameId;

    /**
     * @var Player
     */
    private $playerOnTurn;

    /**
     * @var Player
     */
    private $playerWaiting;

    /**
     * @var MovePoints
     */

    private $movePointsPerTurn;

    /**
     * @var Turn
     */
    private $turn;

    public function __construct(UuidInterface $gameId)
    {
        $this->gameId = $gameId;
    }

    public function getAggregateRootId() : string
    {
        return (string) $this->gameId;
    }

    public static function create(Player $playerOnTurn, Player $playerWaiting, MovePoints $mp) : self
    {
        $game = new self(Uuid::uuid4());

        $game->apply(new GameCreated($playerOnTurn, $playerWaiting, $mp));

        return $game;
    }

    /**
     * @return Player
     */
    public function getPlayerOnTurn(): Player
    {
        return $this->playerOnTurn;
    }

    public function getPlayerWaiting(): Player
    {
        return $this->playerWaiting;
    }

    /**
     * @param Card $card
     */
    public function dealCardForPlayerOnTurn(Card $card) : void
    {
        $this->apply(new CardDealtForPlayerOnTurn($card));
    }

    public function play(Card $card, Player $player) : void
    {
        $event = new Event\CardPlayed($card);

        $this->apply($event);
    }

    protected function applyCardPlayed(Event\CardPlayed $cardPlayed) : void
    {
        $card = $cardPlayed->getCard();
        $this->turn->beeingPlayed($card);
    }

    protected function applyGameCreated(GameCreated $gameCreated) : void
    {
        $this->playerOnTurn = $gameCreated->getPlayerOnTurn();
        $this->playerWaiting = $gameCreated->getPlayerWaiting();
        $this->movePointsPerTurn = $gameCreated->getMovePoints();
        $this->turn = new Turn($this->playerOnTurn, $this->movePointsPerTurn);
    }

    protected function applyCardDealtForPlayerOnTurn(CardDealtForPlayerOnTurn $cardDealtForPlayerOnTurn) : void
    {
        $this->playerOnTurn->dealCard($cardDealtForPlayerOnTurn->getCard());
    }
}
