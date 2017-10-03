<?php

namespace multiworld\util;

use multiworld\MultiWorld;
use pocketmine\event\level\LevelLoadEvent;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\utils\Config;

/**
 * Class DataManager
 * @package multiworld\util
 */
class DataManager implements Listener {

    /** @var  ConfigManager $configManager */
    public $configManager;

    /** @var Data[] $data */
    public $data = [];

    /**
     * DataManager constructor.
     * @param ConfigManager $configManager
     */
    public function __construct(ConfigManager $configManager) {
        $this->getPlugin()->getServer()->getPluginManager()->registerEvents($this, $this->getPlugin());
        $this->configManager = $configManager;
    }

    public function onGenerate(LevelLoadEvent $event) {
        if(empty($this->data[$event->getLevel()->getName()])) {
            $this->addNewData($event->getLevel());
        }
    }

    /**
     * @param Level $level
     * @return Data
     */
    public function addNewData(Level $level):Data {
        if(!file_exists($path = MultiWorld::getInstance()->getDataFolder()."worlds".$level->getName().".yml")) return $data = new Data($this, $level->getName(), $path, 0, 0, 0, 0);
    }

    /**
     * @param $levelName
     * @return Data
     */
    public function getLevelData($levelName): Data {
        return (($data = $this->data[$levelName]) instanceof Data) ? $data : null;
    }


    public function loadData() {
        foreach (glob(ConfigManager::getDataFolder()."worlds/*.yml") as $file) {
            $this->saveDataFromConfig(new Config($file, Config::YAML), basename($file, ".yml"));
        }
    }

    /**
     * @param Config $config
     * @param string $levelName
     */
    public function saveDataFromConfig(Config $config, string $levelName) {
        $this->getPlugin()->getLogger()->notice("Loading data for level {$levelName}...");

        $this->data[$levelName] = new Data($this, $levelName, ConfigManager::getDataFolder()."worlds/{$levelName}.yml");
        if(($data = $this->data[$levelName]) instanceof Data) {

        }
    }

    /**
     * @return ConfigManager $configManager
     */
    public function getConfigManager():ConfigManager {
        return $this->configManager;
    }

    /**
     * @return MultiWorld $multiWorld
     */
    public function getPlugin():MultiWorld {
        return MultiWorld::getInstance();
    }
}