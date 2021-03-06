<?php

namespace Liuggio\Spawn\Process;

use Liuggio\Spawn\Exception\InvalidArgumentException;
use Symfony\Component\Stopwatch\Stopwatch;

class ClosureReturnValue
{
    /**
     * @var string
     */
    private $output;

    /**
     * @var mixed
     */
    private $returnValue;

    /**
     * @var Stopwatch
     */
    private $stopWatch;

    /**
     * @var int
     */
    private $duration;

    /**
     * @var int
     */
    private $startAt;

    /**
     * @var int
     */
    private $memory;

    /**
     * @var string
     */
    private $randomName;

    /**
     * @param string $output
     * @param mixed  $returnValue
     * @param int    $startAt
     * @param int    $duration
     * @param int    $memory
     */
    public function __construct($output = '', $returnValue = '', $startAt = 0, $duration = 0, $memory = 0)
    {
        $this->output = (string) $output;
        $this->returnValue = $returnValue;
        $this->stopWatch = new Stopwatch();
        $this->randomName = uniqid('stop_w');
        $this->duration = (int) $duration;
        $this->startAt = (int) $startAt;
        $this->memory = (int) $memory;
    }

    /**
     * @return ClosureReturnValue
     */
    public static function start()
    {
        $obj = new self();
        ob_start();
        $obj->startStopWatch();

        return $obj;
    }

    /**
     * @param string|null $returnValue
     *
     * @return string
     */
    public function stop($returnValue = null)
    {
        $this->output = ob_get_clean();
        $this->returnValue = $returnValue;
        $event = $this->stopWatch->stop($this->randomName);
        $this->duration = $event->getDuration();
        $this->memory = $event->getMemory();
        $this->startAt = $event->getOrigin();

        return $this->serialize();
    }

    /**
     * @return string
     */
    private function serialize()
    {
        return base64_encode(serialize([$this->output, $this->returnValue, $this->startAt, $this->duration, $this->memory]));
    }

    /**
     * @param string $serialized
     *
     * @return ClosureReturnValue
     */
    public static function unserialize($serialized)
    {
        $value = unserialize(base64_decode($serialized));

        if (!is_array($value) || count(array_intersect_key(array_fill(0, 5, null), $value)) < 5) {
            throw new InvalidArgumentException();
        }

        return new self($value[0], $value[1], $value[2], $value[3], $value[4]);
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return mixed
     */
    public function getReturnValue()
    {
        return $this->returnValue;
    }

    /**
     * Start the logging event.
     */
    public function startStopWatch()
    {
        $this->stopWatch->start($this->randomName);
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @return int
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * @return int
     */
    public function getMemory()
    {
        return $this->memory;
    }
}
