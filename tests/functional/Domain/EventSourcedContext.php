<?php

namespace RstGroup\PhpStudyGroup\CardBattleGame\Tests\Functional\Domain;

use Behat\Behat\Context\Context;
use Broadway\Domain\DomainEventStream;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use Broadway\EventSourcing\EventSourcedAggregateRoot;
use Webmozart\Assert\Assert;

class EventSourcedContext implements Context
{
    protected $aggregateRoot;

    /**
     * @var array
     */
    protected $events = [];

    protected $lastUncommitedEvents = null;

    public function setAggregateRoot(EventSourcedAggregateRoot $aggregateRoot)
    {
        $this->aggregateRoot = $aggregateRoot;
    }

    public function getAggregateRoot(): EventSourcedAggregateRoot
    {
        return $this->aggregateRoot;
    }

    /**
     * @Then aggregate root should have :eventName event
     */
    public function aggregateRootShouldHaveEvent(string $eventName)
    {
        $eventStream = $this->getUncommitedEvents();

        foreach ($eventStream as $event) {
            /** @var DomainMessage $event */
            $payload = $event->getPayload();
            if ($payload instanceof $eventName) {
                return $payload;
            }
        }

        Assert::true(false, sprintf('Aggregate root does not have a %s', $eventName));
    }

    /**
     * @Then :event should have :attribute :value
     */
    public function shouldHave($event, $attribute, $value) : void
    {
        $eventStream = $this->getUncommitedEvents();

        foreach ($eventStream as $event) {
            /** @var DomainMessage $event */
            if ($event->getPayload() instanceof $event) {
                Assert::same($value, $event->{"get".$attribute}());
            }
        }

        throw new \InvalidArgumentException('event not found');
    }

    public function getUncommitedEvents() : DomainEventStream
    {
        if ($this->lastUncommitedEvents === null) {
            $this->lastUncommitedEvents = $this->aggregateRoot->getUncommittedEvents();
        }

        return $this->lastUncommitedEvents;
    }

    public function appendEvent($event) : void
    {
        $this->events[] = $event;
    }

    public function initializeAggregateRootState() : EventSourcedAggregateRoot
    {
        $domainEvents = [];

        foreach ($this->events as $event) {
            $domainEvents[] = DomainMessage::recordNow(
                $this->getAggregateRoot()->getAggregateRootId(),
                0,
                new Metadata([]),
                $event
            );
        }

        $this->aggregateRoot->initializeState(new DomainEventStream($domainEvents));

        return $this->aggregateRoot;
    }
}