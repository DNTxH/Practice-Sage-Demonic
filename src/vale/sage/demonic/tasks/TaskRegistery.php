<?php
namespace vale\sage\demonic\tasks;

use vale\sage\demonic\levels\task\CheckLevelUpTask;
use vale\sage\demonic\Loader;
use vale\sage\demonic\tasks\types\MotdTask;
use vale\sage\demonic\tasks\types\NameTagTask;
use vale\sage\demonic\tasks\types\TPSTask;
use vale\sage\demonic\tasks\types\ClearLag;
use vale\sage\demonic\Trojan\Task\CpsTask;
use vale\sage\demonic\Trojan\Task\MovingTask;
use vale\sage\demonic\tasks\types\PlayTimeUpdateTask;

class TaskRegistery{

	public static function init(): void{
		$scheduler = Loader::getInstance()->getScheduler();
		$scheduler->scheduleRepeatingTask(new NameTagTask(),20);
		$scheduler->scheduleRepeatingTask(new TPSTask(),20);
		$scheduler->scheduleRepeatingTask(new MotdTask(), 20);
		$scheduler->scheduleRepeatingTask(new ClearLag(3600), 20);
        $scheduler->scheduleRepeatingTask(new CheckLevelUpTask(), 20);
        $scheduler->scheduleRepeatingTask(new CpsTask(), 20);
        $scheduler->scheduleRepeatingTask(new MovingTask(), 20);
        $scheduler->scheduleDelayedTask(new PlayTimeUpdateTask(), 20);
	}
}