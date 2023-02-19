<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();
if($_SESSION['user']['user-type'] === UserTypes::ADMIN->value){
$strSub = '';
$subscriptions = new QueryBuilder('subscriptions ORDER BY end_date');
$subscriptions = $subscriptions->project()->select();
foreach($subscriptions as $sub){
  $strSub .= "<tr><th scope=\"row\">{$sub->type}</th><td>{$sub->start_date}</td><td>{$sub->end_date}</td><td>"
      . "<a href=\"reserved-area/admins/modify-sub.php?id={$sub->id}\" class=\"button\">Modifica</a>"
      . "<a href=\"reserved-area/admins/delete-sub.php?id={$sub->id}\" class=\"button\">Cancella</a></td></tr>";
}
echo str_replace("<data-subs></data-subs>", $strSub,
    (new ViewEngine('admins/' . basename(__FILE__, '.php')))->build());
}else{
  exit();
}
