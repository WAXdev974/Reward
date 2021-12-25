<?php

namespace HiroTeam\Reward;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class main extends PluginBase{
  
    /**
     * @var Config
     */
   public $db;

   public function onEnable()
   {
       $this->db = new Config(fille: $this->getDataFloder() . 'db.yml', type:CONFIG::YAML); //Base de donnée !
   }

   public function onCommand(CommandSender $sender, Command $Command, string $label, array $args): bool
   {
       $commandName = strtolwer($command->getName());
       if($sender instanceof Player){

        switch ($commandName){
            case 'Reward':
                $playerName = $sender->getName();
                $time = $this->db->get($playerName);
                $timeNow = time();
                if(empty($time)){
                    $time = 0;
                }
                if($timeNow - $time >= (24 * 60 * 60)){ // si il est plus grand que 24 h alors il va avoir des recompense
                    $sender->getInventory()->addItem(Item::get(id:278, meta: 0, count: 1), Item::get(id:285, meta: 0, count: 1 )); // donnez les recompence
                    $this->db->set($playerName, $timeNow);
                    $this->db->save();
                    $sender->sendMessage('§a§l Vous avez bien récupéré votre récompense du jour !');
                }else {
                    $HourMinuteSecond = explode( delimite: ":" gmdate(format: "H:i:s", timestamp:(24 * 60 * 60) - ($timeNow - $time))) // 23:45:35
                    $sender->sendMessage("§4§l il faut encore attendre $HourMinuteSecond[0] heur/s, $HourMinuteSecond[1] minute/s $HourMinuteSecond[2] second/s avant de récupérer ta prochaine récompense ")
                }
                break;
        }
       }
       return true;
   }
}