<?php
require_once __DIR__ . '/../autoloader.php';

use Classes\QueryBuilder;
use Classes\Validation;

$validation = new Validation(json_decode(file_get_contents("php://input"), true));
$validation->addValidator('username', 'not-empty')->addValidator('username', 'min-5-characters')->addValidator('username', 'max-16-characters');
$validation->addValidator('email-address', 'not-empty')->addValidator('email-address', 'min-4-characters')->addValidator('email-address', 'max-64-characters');
$validation->addValidator('password', 'not-empty')->addValidator('password', 'min-5-characters')->addValidator('password', 'max-64-characters');
$validation->addValidator('name', 'not-empty')->addValidator('name', 'min-2-characters')->addValidator('name', 'max-32-characters')->addValidator('name', 'no-number-presence');
$validation->addValidator('surname', 'not-empty')->addValidator('surname', 'min-2-characters')->addValidator('surname', 'max-32-characters')->addValidator('surname', 'no-number-presence');
$validation->addValidator('phone-number', 'not-empty')->addValidator('phone-number', 'min-10-characters')->addValidator('phone-number', 'max-10-characters')->addValidator('phone-number', 'no-characters-presence');
$validation->addValidator('gender', 'exists')->addValidator('gender', 'not-empty')->addValidator('gender', 'check-for-sex');
$validationResponse = $validation->validate();

if ($validationResponse === true) {
    $data = json_decode(file_get_contents("php://input"), true);

    // AGGIUNGERE NEL DB IL SESSO  qui il campo è già presente
    $array = [
        "name" => $data["name"],
        "surname" => $data["surname"],
        "phone_number" => $data["phone-number"],
        "username" => $data["username"],
        "email" => $data["email-address"],
        "password" => md5($data["password"]),
        "type" => "user",
    ];

    $users = new QueryBuilder('users');

    $res = $users->project(['email', 'username'])->where("email = ? OR username = ?")->select([$array['email'], $array['username']]);

    if (empty($res)) {
        $users->insert($array);
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'errorString' => 'Esiste già un utente con questo nome utente o indirizzo e-mail']);
    }
} else {
    echo json_encode(['success' => false, 'errors' => $validationResponse]);
}
?>