<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_1($module)
{
    return Db::getInstance()->execute(
        'ALTER TABLE `' . _DB_PREFIX_ . 'orders` 
    ADD COLUMN `number` TEXT NULL DEFAULT NULL AFTER `pos_operator`,
	ADD COLUMN `status` TEXT NULL DEFAULT NULL AFTER `number`,
	ADD COLUMN `delivery_type` TEXT NULL DEFAULT NULL AFTER `status`,
	ADD COLUMN `creation_date` DATETIME NULL DEFAULT NULL AFTER `delivery_type`,
	ADD COLUMN `advice_date` DATETIME NULL DEFAULT NULL AFTER `creation_date`,
	ADD COLUMN `tracking_number` TEXT NULL DEFAULT NULL AFTER `advice_date`;');
}
