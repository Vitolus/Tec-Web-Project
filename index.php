<?php

require_once __DIR__ . '/autoloader.php';

use Classes\ViewEngine;

(new ViewEngine(basename(__FILE__, '.php')))->buildAndView();