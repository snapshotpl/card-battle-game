<?php

namespace RstGroup\PhpStudyGroup\CardBattleGame\Domain;

final class MovePoints
{
    /**
     * @var int
     */
    private $pointsCount;

    public function __construct(int $pointsCount)
    {
        if ($pointsCount < 0) {
            throw new \DomainException('points count has to be positive');
        }

        $this->pointsCount = $pointsCount;
    }

    public function decrease(self $movePoints)
    {
        $newPoints = $this;
        $newPoints->pointsCount -= $movePoints->pointsCount;

        return $newPoints;
    }

    /**
     * @return int
     */
    public function getPointsCount(): int
    {
        return $this->pointsCount;
    }
}