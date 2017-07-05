<?php

require __DIR__."/../vendor/autoload.php";

if (class_exists(PHPUnit\Framework\Constraint\Constraint::class, true)) {
    class_alias(
        PHPUnit\Framework\Constraint\Constraint::class,
        PHPUnit_Framework_Constraint::class,
        true
    );
}
