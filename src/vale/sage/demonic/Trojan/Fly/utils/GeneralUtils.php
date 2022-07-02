<?php

namespace  vale\sage\demonic\Trojan\Fly\utils;

final class GeneralUtils{

	public static function iterate($iterator, callable $callable) : void{
		foreach($iterator as $key => $val){
			$callable($key, $val);
		}
	}

}