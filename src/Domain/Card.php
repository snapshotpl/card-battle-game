<?php

namespace RstGroup\PhpStudyGroup\CardBattleGame\Domain;

use InvalidArgumentException;

final class Card
{
    const TYPE_DAMAGE = 'damage';
    const TYPE_ARMOR = 'armor';

    private static $allowedTypes = [
        self::TYPE_ARMOR,
        self::TYPE_DAMAGE,
    ];

    private $type;
    private $value;
    private $mpCost;

    public function __construct(string $type, int $value, MovePoints $mpCost)
    {
        if (!in_array($type, self::$allowedTypes, true)) {
            throw new InvalidArgumentException('unknown type');
        }

        $this->type = $type;
        $this->value = $value;
        $this->mpCost = $mpCost;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getMpCost(): MovePoints
    {
        return $this->mpCost;
    }
}
