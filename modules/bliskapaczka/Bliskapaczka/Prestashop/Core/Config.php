<?php

namespace Bliskapaczka\Prestashop\Core;

/**
 * All about module configuration
 */
class Config
{
    private $name = 'bliskapaczka';
    private $tab = 'shipping_logistics';
    private $version = '1.0.2';
    private $displayName = 'Bliskapaczka';
    private $description = 'Bliskapaczka shipping module';
    private $confirmUninstall = 'Are you sure you want to uninstall?';
    private $limitedCountries = array('pl');
    private $delay = 'Już dzisiaj';

    /**
     * Magic method implementation
     *
     * @param string $property
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }
}
