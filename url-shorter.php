#!/usr/bin/env php
<?php

require('classes/Shorter.php');


if ($argc == 2) // ввели ID или ссылку
{
    if (Shorter::isId($argv[1])) { // ввели ID, выдать сохраненную ссылку
        echo Shorter::getUrlByShortId($argv[1]);
    }
    elseif ($argv[1][0] == '-') { // ввели ID для удаления
        echo Shorter::deleteUrl(-1 * $argv[1]);
    }
    else { // ввели ссылку, сохранить и выдать её ID
        echo Shorter::short($argv[1]);
    }

    echo "\n";
}
else // запустили без параметров, выдать список ссылок
{
    $urls = Shorter::getUrls();

    print_r($urls);
}
