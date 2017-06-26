<?php

require __DIR__."/../vendor/autoload.php";

class_alias(
    PHPUnit\Framework\Constraint\Constraint::class,
    PHPUnit_Framework_Constraint::class,
    true
);
