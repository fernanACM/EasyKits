<?php
/**
 *    _____                         _  __  _   _         
 *   | ____|   __ _   ___   _   _  | |/ / (_) | |_   ___ 
 *   |  _|    / _` | / __| | | | | | ' /  | | | __| / __|
 *   | |___  | (_| | \__ \ | |_| | | . \  | | | |_  \__ \
 *   |_____|  \__,_| |___/  \__, | |_|\_\ |_|  \__| |___/
 *                           |___/                        
 *          by AndreasHGK and fernanACM 
 */
declare(strict_types=1);

namespace AndreasHGK\EasyKits\utils;

use pocketmine\player\Player;

use pocketmine\item\Item;

use AndreasHGK\EasyKits\Kit;
use AndreasHGK\EasyKits\manager\CooldownManager;
use AndreasHGK\EasyKits\manager\DataManager;

abstract class TryClaim{

    /**
     * @param Kit $kit
     * @param Player $player
     * @return void
     */
    public static function tryClaim(Kit $kit, Player $player): void{
        try{
            if($kit->claim($player)) $player->sendMessage(LangUtils::getMessage("kit-claim-success", true, ["{NAME}" => $kit->getName()]));

        }catch(KitException $e){
            switch($e->getCode()){
                case 0:
                    $time = CooldownManager::getKitCooldown($kit, $player);
                    $timeString = TimeUtils::intToTimeString($time);
                    $player->sendMessage(LangUtils::getMessage("kit-cooldown-active", true, ["{TIME}" => $timeString]));
                    break;
                case 1:
                    $player->sendMessage(LangUtils::getMessage("kit-insufficient-funds"));
                    break;
                case 2:
                    $player->sendMessage(LangUtils::getMessage("no-economy"));
                    break;
                case 3:
                    $player->sendMessage(LangUtils::getMessage("kit-insufficient-space"));
                    break;
                case 4:
                    $player->sendMessage(LangUtils::getMessage("kit-no-permission"));
                    break;
                default:
                    $player->sendMessage(LangUtils::getMessage("unknown-exception"));
                    break;
            }
        }
    }

    /**
     * @param Player $player
     * @param Item $chestkit
     * @param Kit $kit
     * @return void
     */
    public static function TryChestClaim(Player $player, Item $chestkit, Kit $kit): void{
        try{
            $kit->setPrice(0);
            $kit->setCooldown(0);
            if(!DataManager::getKey(DataManager::CONFIG, "chestKit-locked")) {
                $kit->setLocked(false);
            }
            if($kit->claimFor($player)) $player->sendMessage(LangUtils::getMessage("chestclaim-success", true, ["{NAME}" => $kit->getName()]));
            foreach($player->getInventory()->getContents() as $index => $i){
                if(!$chestkit->equals($i)) continue;
                $i->pop();
                $player->getInventory()->setItem($index, $i);
                return;
            }
        }catch(KitException $e){
            switch($e->getCode()) {
                case 0:
                    $time = CooldownManager::getKitCooldown($kit, $player);
                    $timeString = TimeUtils::intToTimeString($time);
                    $player->sendMessage(LangUtils::getMessage("kit-cooldown-active", true, ["{TIME}" => $timeString]));
                    break;
                case 1:
                    $player->sendMessage(LangUtils::getMessage("kit-insufficient-funds"));
                    break;
                case 2:
                    $player->sendMessage(LangUtils::getMessage("no-economy"));
                    break;
                case 3:
                    $player->sendMessage(LangUtils::getMessage("kit-insufficient-space"));
                    break;
                case 4:
                    $player->sendMessage(LangUtils::getMessage("kit-no-permission"));
                    break;
                default:
                    $player->sendMessage(LangUtils::getMessage("unknown-exception"));
                    break;
            }
        }
    }

    /**
     * @param Player $player
     * @param Kit $kit
     * @return void
     */
    public static function ForceClaim(Player $player, Kit $kit): void{
        $kit = clone $kit;
        $kit->setPrice(0);
        $kit->setCooldown(0);
        $kit->setLocked(false);
        $kit->claim($player);
    }
}