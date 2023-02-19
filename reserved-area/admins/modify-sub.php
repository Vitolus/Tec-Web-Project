<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();

if ($_SESSION['user']['user-type'] === UserTypes::ADMIN->value) {
    $res= (new QueryBuilder('subscriptions'))->where('id = ?')->select([$_GET['id']])[0];
    $str= "<label for='durata'><strong>Durata:</strong></label>
    <input type='text' id='durata' name='durata' value='{$res->type}' required/>

    <label for='dataInizio'><strong>Data di inizio:</strong></label>
    <input type='date' id='dataInizio' name='dataInizio' value='{$res->start_date}' required/>

    <label for='dataFine'><strong>Data di fine:</strong></label>
    <input type='date' id='dataFine' name='dataFine' value='{$res->end_date}' required/>
    ";

    echo str_replace("<dynamic-form></dynamic-form>", $str, (new ViewEngine('../views/admins/' . basename(__FILE__, '.php')))->build());
}
