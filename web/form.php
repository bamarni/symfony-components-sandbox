<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation;

$container = require_once __DIR__.'/../app/container.php';

$request = HttpFoundation\Request::createFromGlobals();

$person = new Sandbox\Entity\Person;

$form = $container['form.factory']
    ->createBuilder('form', $person, array('cascade_validation' => true))
    ->add('name', 'text')
    ->add('gender', 'gender')
    ->add('nicknames', 'collection', array(
        'type' => new Sandbox\Form\Type\NickNameType,
        'allow_add' => true,
        'allow_delete' => true,
    ))
    ->getForm()
;

if ('POST' === $request->getMethod()) {
    $form->bindRequest($request);
    
    if ($form->isValid()) {
        $message = 'The form is valid.';
    } else {
        $message = 'The form is not valid.';
    }
}

// Form view
$formView = $form->createView();

// Rendering the form
?>

<!DOCTYPE html>
<html>
  <body>

    <h1>Form</h1>

    <form action="" method="post" novalidate="novalidate">
        <?php echo isset($message) ? $message : '' ?>

        <?php echo $container['templating.engine']['form']->row($formView['name']) ?>
        <?php echo $container['templating.engine']['form']->row($formView['gender']) ?>

        <h3>NickNames</h3>

        <ul class="nicknames" data-prototype="<?php echo $container['templating.engine']->escape($container['templating.engine']['form']->row($formView['nicknames']->getVar('prototype'))); ?>">
            <?php foreach($formView['nicknames'] as $nickName): ?>
                <li class="nickname"><?php echo $container['templating.engine']['form']->row($nickName['value']) ?></li>
            <?php endforeach; ?>
        </ul>

        <input type="submit" value="OK" />
    </form>


    <script src="js/jquery.min.js"></script>
    
    <script>
    // Get the div that holds the collection of tags
    var collectionHolder = $('ul.nicknames');

    // setup an "add a tag" link
    var $addNickNameLink = $('<a href="#" class="add_nick_name_link">Add a nickname</a>');
    var $newLinkLi = $('<li></li>').append($addNickNameLink);

    jQuery(document).ready(function() {
        collectionHolder.find('li.nickname').each(function() {
            addNickNameFormDeleteLink($(this));
        });
        // add the "add a tag" anchor and li to the tags ul
        collectionHolder.append($newLinkLi);

        $addNickNameLink.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // add a new tag form (see next code block)
            addNickNameForm(collectionHolder, $newLinkLi);
            addNickNameFormDeleteLink($newFormLi);
        });
    });

    function addNickNameForm(collectionHolder, $newLinkLi) {
        // Get the data-prototype we explained earlier
        var prototype = collectionHolder.attr('data-prototype');

        // Replace '__name__' in the prototype's HTML to
        // instead be a number based on the current collection's length.
        var newForm = prototype.replace(/__name__label__/g, '');
        newForm = newForm.replace(/__name__/g, collectionHolder.children().length);

        // Display the form in the page in an li, before the "Add a tag" link li
        var $newFormLi = $('<li></li>').append(newForm);
        $newLinkLi.before($newFormLi);
    }

    function addNickNameFormDeleteLink($nickNameFormLi) {
        var $removeFormA = $('<a href="#">delete this nickname</a>');
        $nickNameFormLi.append($removeFormA);

        $removeFormA.on('click', function(e) {
            // prevent the link from creating a "#" on the URL
            e.preventDefault();

            // remove the li for the tag form
            $nickNameFormLi.remove();
        });
    }
    </script>

  </body>
</html>
