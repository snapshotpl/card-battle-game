<?php

namespace RstGroup\PhpStudyGroup\CardBattleGame\Domain;

final class Turn
{
    /**
     * @var Player
     */
    private $player;
    /**
     * @var MovePoints
     */
    private $movePoints;

    public function __construct(Player $player, MovePoints $movePoints)
    {
        $this->player = $player;
        $this->movePoints = $movePoints;
    }

    public function beeingPlayed(Card $card)
    {
        $this->movePoints = $this->movePoints->decrease($card->getMpCost());
        $this->player->beeingPlayed($card);
    }

    public function getMovePoints() : MovePoints
    {
        return $this->movePoints;
    }
}
