<?php

namespace Liuggio\Concurrent\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Stopwatch\StopwatchEvent;

class LoopCompletedEvent extends Event
{
    /** @var StopwatchEvent */
    private $stopwatchEvent;
    /** @var int */
    private $exitCode;

    /**
     * LoopCompletedEvent constructor.
     *
     * @param StopwatchEvent $stopwatchEvent
     */
    public function __construct(StopwatchEvent $stopwatchEvent, $exitCode)
    {
        $this->stopwatchEvent = $stopwatchEvent;
        $this->exitCode = $exitCode;
    }

    /**
     * @return StopwatchEvent
     */
    public function getStopwatchEvent()
    {
        return $this->stopwatchEvent;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}
