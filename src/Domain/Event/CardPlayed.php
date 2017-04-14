<?php

namespace RstGroup\PhpStudyGroup\CardBattleGame\Domain\Event;

use RstGroup\PhpStudyGroup\CardBattleGame\Domain\Card;

final class CardPlayed
{
    private $card;

    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    public function getCard() : Card
    {
        return $this->card;
    }
}