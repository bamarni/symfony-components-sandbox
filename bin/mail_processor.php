<?php

use Sandbox\Document\Email;

// Includes the autoloader
require_once __DIR__.'/../vendor/autoload.php';

$mongo = new Mongo();
$collection = $mongo->selectDB('mailer')->mail;

$cursor = $collection->find();

while (1) {
    while (!$cursor->hasNext()) {
        echo "No pending email.\n";
        sleep(10);
    }

    $email = Email::createFromArray($cursor->getNext());

    echo sprintf("Sending email #%s.\n", $email->getId());

    // Send the email
    // ...
}
