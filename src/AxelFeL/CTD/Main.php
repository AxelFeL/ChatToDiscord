<?php

namespace AxelFeL\CTD;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent, PlayerChatEvent};
use pocketmine\event\Listener;

use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed;

class Main extends PluginBase implements Listener {
  
  public $api;
  
  public function onEnable() : void{
    $this->saveResource("config.yml");
    $this->getLogger()->info("Plugin Enabled!");
    $api = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
  }
  
  public function onChat(PlayerChatEvent $ev){
    $player = $ev->getPlayer();
    $name = $player->getName();
    $msg = $ev->getMessage();
    $web = new Webhook($this->getConfig()->get("webhook-url"));
    $mes = new Message();
    $emb = new Embed();
    $rank = $this->getPlayerRank($player);
    $emb->setColor(0x3080FF);
    $emb->setTitle($this->getConfig()->get("msg-title"));
    $emb->setDescription("[".$rank."] ".$name.": ".$msg);
    $mes->addEmbed($emb);
    $web->send($mes);
  }
  
  public function onJoin(PlayerJoinEvent $ev){
    $player = $ev->getPlayer();
    $name = $player->getName();
    $rank = $this->getPlayerRank($player);
    $web = new Webhook($this->getConfig()->get("webhook-url"));
    $mes = new Message();
    $emb = new Embed();
    $emb->setColor(0x4DDB00);
    $emb->setTitle($this->getConfig()->get("msg-title"));
    $emb->setDescription("[".$rank."] ".$name." has joined the server!");
    $mes->addEmbed($emb);
    $web->send($mes);
  }
  
  public function onQuit(PlayerQuitEvent $ev){
    $player = $ev->getPlayer();
    $name = $player->getName();
    $rank = $this->getPlayerRank($player);
    $web = new Webhook($this->getConfig()->get("webhook-url"));
    $mes = new Message();
    $emb = new Embed();
    $emb->setColor(0xFF0000);
    $emb->setTitle($this->getConfig()->get("msg-title"));
    $emb->setDescription("[".$rank."] ".$name." has left the server!");
    $mes->addEmbed($emb);
    $web->send($mes);
  }
  
  //Code from PurePermsScore
  public function getPlayerRank(Player $player): string{
      $api = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
      $group = $api->getUserDataMgr()->getData($player)["group"];

      return $group ?? "No Rank";
  }
}
