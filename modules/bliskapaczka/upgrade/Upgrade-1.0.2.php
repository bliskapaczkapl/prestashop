<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_1_0_2($module)
{

    $AdminBliskaOrdersId = Db::getInstance()->getValue(
        'SELECT id_tab FROM `ps_tab` WHERE class_name = \'AdminBliskaOrders\';'
    );

    if(!$AdminBliskaOrdersId) {
        //nowy tab
        Db::getInstance()->execute(
            'INSERT INTO ' . _DB_PREFIX_ . 'tab` (`id_parent`, `class_name`, `position`) VALUES (\'10\', \'AdminBliskaOrders\', \'7\');'
        );

        //id nowego tabu
        $lastInsertId = Db::getInstance()->execute(
            'SELECT LAST_INSERT_ID();'
        );

        //nowy tab w jezyku angielskim
        Db::getInstance()->execute(
            'INSERT INTO ' . _DB_PREFIX_ . 'tab_lang` (`id_tab`, `id_lang`, `name`) VALUES (' . $lastInsertId . ', \'1\', \'Bliska Orders\');'
        );
    }

    return true;
}
