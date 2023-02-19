<?php

require_once __DIR__ . '/../autoloader.php';
use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

$page = NULL;
$val = NULL;
session_start();

if($_SESSION['user']['user-type']){
        $users = new QueryBuilder('users');
        $val = $users->project(['email', 'username','phone_number', 'id'])->where("id = ?")->select([$_SESSION['user']['user-id']])[0];
        if(!empty($val)){formData($page,$val);}
}

function formData($page,$val)
{
    if (!empty($val)) {
        $str= "<label for='username'><strong>Username:</strong></label>
            <input type='text' id='username' name='username' minlength='5' maxlength='16' value='{$val->username}'>

            <label for='email'><strong>E-mail:</strong></label>
            <input type='text' id='email' name='email' minlength='4' maxlength='64' value='{$val->email}'>

            <label for='password'><strong>Password:</strong></label>
            <input type='password' id='password' name='password' minlength='5' maxlength='32' value=''>
    
            <label for='cell'><strong>Telefono cellulare:</strong></label>
            <input type='text' id='cell' name='cell' minlength='10' maxlength='10' value='{$val->phone_number}'>";

      echo str_replace("<data-group></data-group>", $str, (new ViewEngine('reserved-area/'. basename(__FILE__, '.php')))->build());
    } else {
        exit();
    }
}
?>