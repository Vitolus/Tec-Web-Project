<?php
require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Enums\UserTypes;

session_start();

if ($_SESSION['user']['user-type'] === UserTypes::INSTRUCTOR->value) {
    (new ViewEngine('instructor/' . basename(__FILE__, '.php')))->buildAndView();
} else {
  exit();
}
