<?php

namespace Liuggio\Concurrent\Process;

use Liuggio\Concurrent\Process\Channel\Channel;
use Liuggio\Concurrent\CommandLine;

class ProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldCreateAProcessTests()
    {
        $assertionCommandLine = 'bin/phpunit fileA';
        $channelNumber = 2;
        $channel = Channel::createAWaiting($channelNumber, 10);
        $process = $this->getMockBuilder('\Liuggio\Concurrent\Process\Process')
            ->disableOriginalConstructor()
            ->getMock();
        $channel = $channel->assignToAProcess($process);
        $envs = new ProcessEnvironment($channel, 'fileA', 11);

        $process = new Process(
            new CommandLine($assertionCommandLine),
            $envs
        );

        $this->assertInstanceOf('\Liuggio\Concurrent\Process\Process', $process);
        $this->assertEquals('bin/phpunit fileA', $process->getCommandLine());
        $this->assertEquals(array(
            0 => 'ENV_TEST_CHANNEL=2',
            1 => 'ENV_TEST_CHANNEL_READABLE=test_2',
            2 => 'ENV_TEST_CHANNELS_NUMBER=10',
            3 => 'ENV_TEST_ARGUMENT=fileA',
            4 => 'ENV_TEST_INC_NUMBER=11',
            5 => 'ENV_TEST_IS_FIRST_ON_CHANNEL=1', ),
            $process->getenv());
    }
}
