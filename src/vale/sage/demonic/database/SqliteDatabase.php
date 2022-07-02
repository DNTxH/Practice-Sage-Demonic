<?php

declare(strict_types = 1);

namespace vale\sage\demonic\database;

use vale\sage\demonic\Loader;

class SqliteDatabase {

    private \Sqlite3 $database;

    /**
     * @param Loader $loader
     */
    public function __construct(private Loader $loader){
        $this->register();
    }

    public function register() : void {
        $this->database = new \Sqlite3($this->loader->getDataFolder() . "database.db");
        $this->database->exec("CREATE TABLE IF NOT EXISTS homes(player STRING, name STRING, position STRING);");
    }

    public function unregister(): void{
        $this->database->close();
    }

    /**
     * @return \Sqlite3
     */
    public function getDatabase(): \Sqlite3{
        return $this->database;
    }

    /**
     * @param string $player
     * @param string $name
     * @param string $position
     * @return void
     */
    public function addHome(string $player, string $name, string $position): void{
        $this->removeHome($player, $name);
        $db = $this->database->prepare("INSERT INTO homes(player, name, position) VALUES(:player, :name, :position);");
        $db->bindValue(":player", $player);
        $db->bindValue(":name", $name);
        $db->bindValue(":position", $position);
        $db->execute();
    }

    /**
     * @param string $player
     * @param string $name
     * @return void
     */
    public function removeHome(string $player, string $name):void{
        $db = $this->database->prepare("DELETE FROM homes WHERE (player='" . $player . "' AND name='" . $name . "');");
        $db->execute();
    }

    /**
     * @param string $player
     * @param string $name
     * @return string|null
     */
    public function getHome(string $player, string $name):?string{
        try{
            $result = $this->database->query("SELECT position FROM homes WHERE player='" . $player . "' AND name='" . $name . "'");
            $resultArray = $result->fetchArray(SQLITE3_ASSOC);
            return $resultArray["position"];
        }catch(\ErrorException $er){
            return null;
        }
    }

    /**
     * @param string $player
     * @return array
     */
    public function getHomes(string $player): array{
        $array = [];
        $list = $this->database->query("SELECT * FROM homes WHERE player='" . $player . "'");
        while($element = $list->fetchArray(SQLITE3_ASSOC)){
            $array[] = $element["name"];
        }
        return $array;
    }
}