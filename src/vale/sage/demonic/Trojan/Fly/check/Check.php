<?php


namespace  vale\sage\demonic\Trojan\Fly\check;

use  vale\sage\demonic\Trojan\Fly\data\PlayerData;
use  vale\sage\demonic\Trojan\Fly\Esoteric;
use  vale\sage\demonic\Trojan\Fly\Settings;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\CorrectPlayerMovePredictionPacket;
use pocketmine\network\mcpe\protocol\ServerboundPacket;
use pocketmine\timings\TimingsHandler;
use vale\sage\demonic\Trojan\TrojanAPI;
use function array_keys;
use function count;
use function max;

abstract class Check{

	public static array $settings = [];
	public static array $timings = [];
	/** @var int[] */
	private static array $TOTAL_VIOLATIONS = [];
	public string $name;
	public string $subType;
	public string $description;
	public bool $experimental;
	public int $violations = 0;
	public int $buffer = 0;

	public function __construct(string $name, string $subType, string $description, bool $experimental = false){
		$this->name = $name;
		$this->subType = $subType;
		$this->description = $description;
		$this->experimental = $experimental;
		if(!isset(self::$settings["$name:$subType"])){
			$settings = Esoteric::getInstance()->getSettings()->getCheckSettings($name, $subType);
			if($settings === null){
				$settings = ["enabled" => true, "punishment_type" => "none", "max_vl" => 20];
			}
			self::$settings["$name:$subType"] = $settings;
		}
		if(!isset(self::$timings["$name:$subType"])){
			self::$timings["$name:$subType"] = new TimingsHandler("Esoteric Check $name($subType)");
		}
	}

	public function getData() : array{
		return ["violations" => $this->violations, "description" => $this->description, "full_name" => $this->name . " ({$this->subType})", "name" => $this->name, "subType" => $this->subType];
	}

	public function getTimings() : TimingsHandler{
		return self::$timings["{$this->name}:{$this->subType}"];
	}

	public abstract function inbound(ServerboundPacket $packet, PlayerData $data) : void;


	public function enabled() : bool{
		return $this->option("enabled");
	}

	protected function option(string $option, $default = null){
		return self::$settings["{$this->name}:{$this->subType}"][$option] ?? $default;
	}

	protected function flag(PlayerData $data, array $extraData = []) : void{
		$extraData["ping"] = $data->player->getNetworkSession()->getPing() ?? "N/A";
		$dataString = self::getDataString($extraData);
		if(!$this->experimental){
			++$this->violations;
			if(!isset(self::$TOTAL_VIOLATIONS[$data->player->getName()])){
				self::$TOTAL_VIOLATIONS[$data->player->getName()] = 0;
			}
			++self::$TOTAL_VIOLATIONS[$data->player->getName()];
			$banwaveSettings = Esoteric::getInstance()->getSettings()->getWaveSettings();
			if($banwaveSettings["enabled"] && self::$TOTAL_VIOLATIONS[$data->player->getName()] >= $banwaveSettings["violations"] && !$data->player->hasPermission("ac.bypass")){
				TrojanAPI::addFlag($data->player->getName(), "bhop", true);
			}
            TrojanAPI::addFlag($data->player->getName(), "bhop", false);
		}
		$this->warn($data, $extraData);
	}

	public static function getDataString(array $data) : string{
		$dataString = "";
		$n = count($data);
		$i = 1;
		foreach($data as $name => $value){
			$dataString .= "$name=$value";
			if($i !== $n)
				$dataString .= " ";
			$i++;
		}
		return $dataString;
	}

	public function getCodeName() : string{
		return $this->option("code", "{$this->name}({$this->subType})");
	}


	protected function warn(PlayerData $data, array $extraData) : void{
		TrojanAPI::addFlag($data->player->getName(), "bhop");
        TrojanAPI::update($data->player->getName());
	}



	protected function setback(PlayerData $data) : void{
		if(!$data->hasMovementSuppressed && $this->option("setback", false)){
			$type = Esoteric::getInstance()->getSettings()->getSetbackType();
			switch($type){
				case Settings::SETBACK_SMOOTH:
					$delta = ($data->packetDeltas[0] ?? new Vector3(0, -0.08 * 0.98, 0));
					$packet = CorrectPlayerMovePredictionPacket::create(($data->onGround ? $data->lastLocation : $data->lastOnGroundLocation)->add(0, 1.62, 0), $delta, $data->onGround, array_keys($data->packetDeltas)[0] ?? 0);
					$data->player->getNetworkSession()->sendDataPacket($packet);
					break;
				case Settings::SETBACK_INSTANT:
					$position = $data->onGround ? $data->lastLocation : $data->lastOnGroundLocation;
					$data->player->teleport($position, $data->currentYaw, 0);
					break;
			}
			$data->hasMovementSuppressed = true;
		}
	}

	protected function reward(float $sub = 0.01) : void{
		$this->violations = max($this->violations - $sub, 0);
	}

}