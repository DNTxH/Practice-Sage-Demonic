<?php

namespace  vale\sage\demonic\Trojan\Fly\utils;

final class Pair{

	public $x, $y;

	public function __construct($x, $y){
		$this->x = $x;
		$this->y = $y;
	}

	public function getX(){
		return $this->x;
	}

	public function getY(){
		return $this->y;
	}

}