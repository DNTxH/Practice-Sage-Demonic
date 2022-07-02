<?php

declare(strict_types=1);

namespace vale\sage\demonic\provider\task;


use pocketmine\scheduler\Task;
use vale\sage\demonic\Loader;
use vale\sage\demonic\provider\thread\MySQLThread;

class ReadResultsTask extends Task
{
	/**
	 * ReadResultsTask constructor.
	 *
	 * @param MySQLThread $thread
	 */
	public function __construct(
		private MySQLThread $thread
	){}

	public function onRun(): void
	{
		if (!$this->thread->isRunning()) {
			$this->thread = Loader::getInstance()->getMysqlProvider()->createNewThread();
		}
		$this->thread->checkResults();
	}
}
