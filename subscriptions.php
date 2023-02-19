<?php

require_once __DIR__ . '/autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;

$strSub = '';

$subscriptions = new QueryBuilder('subscriptions');
$subscriptions = $subscriptions->project(['type', 'start_date', 'end_date'])->select();

foreach ($subscriptions as $sub) {
  $strSub .= "<tr><th scope=\"row\">{$sub->type}</th><td>{$sub->start_date}</td><td>{$sub->end_date}</td></tr>";
}

echo str_replace("<data-subs></data-subs>", $strSub, (new ViewEngine(basename(__FILE__, '.php')))->build());
