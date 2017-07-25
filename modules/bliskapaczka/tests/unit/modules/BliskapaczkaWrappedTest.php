<?php

use PHPUnit\Framework\TestCase;

class BliskapaczkaWrappedTest extends TestCase
{
    public function testFileExists()
    {
        $this->assertTrue(file_exists($GLOBALS['ROOT_DIR'] . '/modules/bliskapaczka/bliskapaczka_wrapped.php'));
    }
}
