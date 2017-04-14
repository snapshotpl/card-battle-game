<?php

namespace RstGroup\PhpStudyGroup\CardBattleGame\Domain\Event;

use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Card;

final class CardDealtForPlayerOnTurn
{
    private $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    /**
     * @return Card
     */
    public function getCard(): Card
    {
        return $this->card;
    }
}
