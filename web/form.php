<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

// Includes the autoloader and the container
require_once __DIR__.'/../vendor/autoload.php';
$container = require_once __DIR__.'/../app/container.php';

// Creates the request
$request = Request::createFromGlobals();

// New email document
$email = new Sandbox\Document\Email;

// Creates the form
$form = $container['form.factory']
    ->createBuilder('form', $email)
    ->add('email', 'email')
    ->add('subject', 'text')
    ->add('content', 'textarea')
    ->getForm()
;

$valid = null;

if ('POST' === $request->getMethod()) {
    $form->bindRequest($request);

    // If the form is valid
    if ($valid = $form->isValid()) {
        $m = new Mongo();
        $db = $m->selectDB('mailer');

        // The document is persisted
        $db->mail->insert($email->toArray());
    }
}

// Form view
$formView = $form->createView();

// Preparing the response
ob_start();
?>

<!DOCTYPE html>
<html>
  <body>
    <?php if (true === $valid): ?>

        <h3>Thanks for your message, we'll get back to you ASAP.</h3>

    <?php else : ?>
        <h1>Form</h1>

        <form action="" method="post" novalidate="novalidate">

            <?php if (false === $valid): ?>
                <h3>Formulaire invalide</h3>
            <?php endif ?>

            <?php echo $container['templating.engine']['form']->widget($formView) ?>

            <input type="submit" value="OK" />

        </form>
    <?php endif ?>
  </body>
</html>

<?php

$content = ob_get_clean();
$status = (true === $valid) ? 202 : 200; // Status code 202 if document created, 200 otherwise

$response = new Response($content, $status);

// Sends the response to the client
$response->send();
