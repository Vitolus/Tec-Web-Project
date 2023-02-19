<?php

require_once __DIR__ . '/../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();

if ($_SESSION['user']['user-type'] === UserTypes::INSTRUCTOR->value) {
    $page = new ViewEngine('../views/instructor/' . basename(__FILE__, '.php'));
    $page->build();

    $res = (new QueryBuilder('courses'))->where('id = ? AND user_id = ?')->select([$_GET['id'], $_SESSION["user"]["user-id"]])[0];

    $str =
        "<label for='title'><strong>Titolo corso:</strong></label>
    <input type='text' id='title' name='title' minlength='5' maxlength='32' value='{$res->title}' required/>

    <label for='description'><strong>Descrizione:</strong></label>
    <textarea id='description' name='description' required>{$res->description}</textarea>

    <label for='partecipants'><strong>Numero partecipanti:</strong></label>
    <input type='number' id='partecipants' name='partecipants' value='{$res->max_partecipants}' required/>
    ";

    $html = str_replace("<dynamic-form></dynamic-form>", $str, $page->getContent());
    echo $html;
}
