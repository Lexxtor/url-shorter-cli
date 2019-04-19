<?php

require __DIR__.'/Shorter.php';

class ShorterTest extends PHPUnit_Framework_TestCase
{
    public function testTokenize()
    {
        $this->assertEquals(Shorter::tokenize(12), 'c');
    }

    public function testGetUrlByShortId()
    {
        $this->assertEquals(Shorter::getUrlByShortId('AA@@'), false);
        $this->assertEquals(Shorter::getUrlByShortId('1'), 'http://verdure.net');
    }
}