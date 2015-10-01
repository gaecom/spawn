<?php

namespace Liuggio\Spawn;

/**
 *  Number of processors seen by the OS and used for processes scheduling.
 */
class ProcessorCounter
{
    const PROC_DEFAULT_NUMBER = 4;
    const PROC_CPUINFO = '/proc/cpuinfo';

    /**
     * @var int|null
     */
    public static $count = null;

    /**
     * @var string
     */
    private $procCPUInfo;

    /**
     * @var string
     */
    private $os;

    /**
     * @param string|null $procCPUInfo
     * @param string|null $os
     */
    public function __construct($procCPUInfo = null, $os = null)
    {
        $this->procCPUInfo = $procCPUInfo ?: self::PROC_CPUINFO;
        $this->os = $os ?: PHP_OS;
    }

    /**
     * @return int
     */
    public function execute()
    {
        if (null !== self::$count) {
            return self::$count;
        }
        self::$count = $this->readFromProcCPUInfo();

        return self::$count;
    }

    /**
     * @return int
     */
    private function readFromProcCPUInfo()
    {
        if ($this->os === 'Darwin') {
            $processors = system('/usr/sbin/sysctl -n hw.physicalcpu');
            if ($processors !== false && $processors) {
                return $processors;
            }
        } elseif ($this->os === 'Linux') {
            if (is_file($this->procCPUInfo) && is_readable($this->procCPUInfo)) {
                try {
                    $contents = trim(file_get_contents($this->procCPUInfo));

                    return substr_count($contents, 'processor');
                } catch (\Exception $e) {
                }
            }
        }

        return self::PROC_DEFAULT_NUMBER;
    }
}
