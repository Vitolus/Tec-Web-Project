<?php

require_once __DIR__ . '/../../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();

if ($_SESSION['user']['user-type'] === UserTypes::USER->value) {
    $page = new ViewEngine('user/' . basename(__FILE__, '.php'));
    $page->build();
    
    $table = new QueryBuilder("subscriptions");
    $val = $table->where("id = ?")->project(['start_date','end_date'])->select([$_GET['id']]);
    if(!empty($val))
    {
        // missing one check
        
        $page = str_replace('{{ start_date }}', $val[0]->start_date, $page->getContent());
        echo str_replace('{{ end_date }}', $val[0]->end_date, $page);
    }
    else
    {
        exit();
    }
} else {
    exit();
}
?>