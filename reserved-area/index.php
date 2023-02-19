<?php

require_once __DIR__ . '/../autoloader.php';

use Classes\ViewEngine;
session_start();

if (isset($_SESSION['user'])) {
    header('Location: control-panel.php');
}

(new ViewEngine('reserved-area/' . basename(__FILE__, '.php')))->buildAndView();
?>