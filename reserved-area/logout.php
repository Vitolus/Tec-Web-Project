<?php
require_once __DIR__ . '/../autoloader.php';

session_start();
session_destroy();

header("Location: ../index.php");
