<?php

require_once __DIR__ . '/../../../autoloader.php';

use Classes\ViewEngine;
use Classes\QueryBuilder;
use Enums\UserTypes;

session_start();
if ($_SESSION['user']['user-type'] === UserTypes::USER->value) {

    $table = new QueryBuilder('subscriptions, users');
    $array = [$_SESSION['user']['user-id']];
    $val = $table->where("users.subscription_id = subscriptions.id AND users.id = ?")->project(['subscriptions.type', 'start_date', 'end_date'])->select($array);

    $pageHtml = (new ViewEngine('user/' . basename(__FILE__, '.php')))->build();

    if (empty($val)) {
        $dataSubscription = "<p>Non hai sottoscritto alcun abbonamento. Torna alla pagina precedente e clicca sul pulsante \"Abbonati\" per scegliere uno fra quelli disponibili.</p>";
    } else {
        $dataSubscription = "
        <p>
          Qui puoi vedere l'abbonamento che hai sottoscritto.
        </p>
        <p>
        Tipologia: {$val[0]->type}
        </p>
        <p>
            Data di inizio: {$val[0]->start_date}
        </p>
        <p>
            Data di fine: {$val[0]->end_date}
        </p>
        <input type='submit' value='Annulla abbonamento' class='button'/>
        ";
    }

    echo str_replace("<data-subscription></data-subscription>", $dataSubscription, $pageHtml);
} else {
    exit();
}
