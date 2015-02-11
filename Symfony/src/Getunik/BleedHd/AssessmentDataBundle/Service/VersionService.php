<?php

namespace Getunik\BleedHd\AssessmentDataBundle\Service;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Process\Process;


class VersionService
{
	private $environment;
	private $allowGitVersion;
	private $configVersion;
	private $gitVersion;

	public function __construct(Kernel $kernel, $allowGitVersion, $configVersion)
	{
		$this->environment = $kernel->getEnvironment();
		$this->allowGitVersion = $allowGitVersion;
		$this->configVersion = $configVersion;

		$process = new Process('git describe --tags');
		$process->run();

		// executes after the command finishes
		if ($process->isSuccessful())
		{
			$this->gitVersion = $process->getOutput();
		}
	}

	public function getVersion()
	{
		return $this->environment . ' ' . $this->configVersion . ' / ' . $this->gitVersion;
	}
}
