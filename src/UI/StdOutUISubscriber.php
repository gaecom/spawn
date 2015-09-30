<?php

namespace Liuggio\Spawn\UI;

use Liuggio\Spawn\Event\EventsName;
use Liuggio\Spawn\Event\ProcessCompletedEvent;
use Liuggio\Spawn\Event\ProcessGeneratedBufferEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class StdOutUISubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return array(
           // EventsName::INPUT_LINE_ENQUEUED => array('onInputLineEnqueued', 100),
           // EventsName::INPUT_LINE_DEQUEUED => array('onInputLineDequeued', 100),
            EventsName::QUEUE_IS_FROZEN => array('onFrozenQueue', 100),
           // EventsName::QUEUE_IS_EMPTY => array('onQueueEmptied', 100),
            EventsName::PROCESS_STARTED => array('onProcessStarted', 100),
            EventsName::PROCESS_COMPLETED => array('onProcessCompleted', 100),
           // EventsName::PROCESS_GENERATED_BUFFER => array('onGeneratedBuffer', 100),
        );
    }

    public function __call($name, $arguments)
    {
        echo ' - called:'.$name.PHP_EOL;
    }

    public function onProcessCompleted(ProcessCompletedEvent $event)
    {
        echo sprintf("%s] onProcessCompleted: [%s] on [%s] with %s\n",
            $event->getProcess()->isSuccessful() ? '✅' : '✗',
            $event->getProcess()->getIncrementalNumber(),
            $event->getProcess()->getChannel(),
            $event->getProcess()->getCommandLine()

        );
    }

    public function onGeneratedBuffer(ProcessGeneratedBufferEvent $event)
    {
        $err = trim($event->getProcess()->getIncrementalErrorOutput());
        $out = trim($event->getProcess()->getIncrementalOutput());
        if (empty($err) && empty($out)) {
            return;
        }

        echo sprintf(" - buffer: [%s] on [%s] with %s \n  out %s|err %s\n",
            $event->getProcess()->getIncrementalNumber(),
            $event->getProcess()->getChannel(),
            $event->getProcess()->getCommandLine(),
            $out,
            $err
        );
    }
}
