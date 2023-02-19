<?php
require_once __DIR__ . '/../autoloader.php';

use Classes\QueryBuilder;
use Classes\Validation;

$validation = new Validation(json_decode(file_get_contents("php://input"), true));
$validation->addValidator('name', 'not-empty')->addValidator('name', 'min-2-characters')->addValidator('name', 'max-32-characters');
$validation->addValidator('surname', 'not-empty')->addValidator('surname', 'min-2-characters')->addValidator('surname', 'max-32-characters');
$validation->addValidator('email-address', 'not-empty')->addValidator('email-address', 'email')->addValidator('surname', 'min-4-characters')->addValidator('email-address', 'max-64-characters');
$validation->addValidator('phone-number', 'not-empty')->addValidator('phone-number', 'min-10-characters')->addValidator('phone-number', 'max-10-characters');

$validationResponse = $validation->validate();

if ($validationResponse === true) {
    $data = json_decode(file_get_contents("php://input"), true);
    $array = [
        "name" => $data["name"],
        "surname" => $data["surname"],
        "email" => $data["email-address"],
        "phone" => $data["phone-number"],
    ];

    $users = new QueryBuilder('contacts');

    $res = $users->insert($array);

    if ($res) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    echo json_encode(['validation' => false, 'errors' => $validationResponse]);
}
?>