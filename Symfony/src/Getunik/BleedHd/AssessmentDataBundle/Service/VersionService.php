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

		if ($this->allowGitVersion)
		{
			$process = new Process('git describe --tags');
			$process->run();

			// executes after the command finishes
			if ($process->isSuccessful())
			{
				$this->gitVersion = trim($process->getOutput());
			}
		}
	}

	public function getVersion($internal = false)
	{
		if ($internal)
		{
			return (isset($this->gitVersion) ? $this->gitVersion : $this->configVersion . '*');
		}
		else
		{
			return $this->configVersion;
		}
	}
}
