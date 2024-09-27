<?php
//----------------------------------------------------------------------
// src/Service/LogTools.php
//----------------------------------------------------------------------
namespace App\Service;

use Psr\Log\LoggerInterface;

class LogTools
{
    public function __construct(LoggerInterface $ouchLogger)
    {
        $this->logger = $ouchLogger;
    }

    public function ouch($msg)
    {
        error_log("\n".$msg."\n",3,"ouch.log");
    }

    public function errorlog($msg)
	{
		$backTrace = debug_backtrace()[1];

        $backTraceMethod = $backTrace['function'];
		$backTraceClass = $backTrace['class'];

		$this->logger->error('', array(
			'class'				=>	$backTraceClass,
			'method'			=>	$backTraceMethod,
			'msg'				=>	$msg,
		));
	}
}
