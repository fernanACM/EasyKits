<?php

declare(strict_types=1);

namespace AndreasHGK\EasyKits\ui;

use AndreasHGK\EasyKits\Kit;
use AndreasHGK\EasyKits\manager\KitManager;
use AndreasHGK\EasyKits\utils\LangUtils;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\Player;

class EditkitMainForm {

    public static function sendTo(Player $player, Kit $kit): void
    {

        $ui = new SimpleForm(function(Player $player, $data) use($kit){
            if($data === null){
                $player->sendMessage(LangUtils::getMessage("editkit-cancelled"));
                return;
            }
            switch ($data){
                case "general":
                    EditkitGeneralForm::sendTo($player, $kit);
                    break;
                case "potions":
                    EditkitPotionSelectForm::sendTo($player, $kit);
                    break;
                case "commands":
                    EditkitCommandsForm::sendTo($player, $kit);
                    break;
                case "effects":
                    $player->sendMessage(LangUtils::getMessage("coming-soon"));
                    break;
            }

            return;
        });
        $ui->setTitle(LangUtils::getMessage("editkit-title"));
        $ui->setContent(LangUtils::getMessage("editkit-main-text", true, ["{NAME}" => $kit->getName()]));
        $ui->addButton(LangUtils::getMessage("editkit-edit-general"), -1, "", "general");
        $ui->addButton(LangUtils::getMessage("editkit-edit-potions"), -1, "", "potions");
        $ui->addButton(LangUtils::getMessage("editkit-edit-commands"), -1, "", "commands");
        $ui->addButton(LangUtils::getMessage("editkit-edit-effects"), -1, "", "effects");
        $player->sendForm($ui);
    }

}