<?php

declare(strict_types=1);

namespace RstGroup\PhpStudyGroup\CardBattleGame\Domain\Event;

use RstGroup\PhpStudyGroup\CardBattleGame\Domain\MovePoints;
use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Player;

final class GameCreated
{
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
    private $movePoints;

    public function __construct(Player $playerOnTurn, Player $playerWaiting, MovePoints $mp)
    {
        $this->playerOnTurn = $playerOnTurn;
        $this->playerWaiting = $playerWaiting;
        $this->movePoints = $mp;
    }

    /**
     * @return Player
     */
    public function getPlayerOnTurn(): Player
    {
        return $this->playerOnTurn;
    }

    /**
     * @return Player
     */
    public function getPlayerWaiting(): Player
    {
        return $this->playerWaiting;
    }

    /**
     * @return MovePoints
     */
    public function getMovePoints(): MovePoints
    {
        return $this->movePoints;
    }
}