<?php

require_once __DIR__ . '/autoloader.php';

use Classes\ViewEngine;

session_start();

if (isset($_SESSION['user'])) {
    header("location: reserved-area");
}

(new ViewEngine(basename(__FILE__, '.php')))->buildAndView();
