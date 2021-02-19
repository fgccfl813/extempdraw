<?php
require_once 'app/init.php';

$drawRound = filter_input(INPUT_GET,'r', FILTER_SANITIZE_NUMBER_INT);
$drawCode = filter_input(INPUT_GET, 'dc', FILTER_SANITIZE_STRING);
$drawCode = preg_replace('/\W+/', '', strtoupper(trim($drawCode)));

$flash = new FlashMessage();
$db = Database::connect()->open();

// Validate the contestant code and determine whether they're in the round.
$cValidate = $db->prepare('SELECT COUNT(*) FROM contestants WHERE CONCAT(c_code, LEFT(c_firstname, 1), LEFT(c_lastname, 1)) = ?');
$cValidate->execute([$drawCode]);
if ($cValidate->fetchColumn() == 0) {
    $flash->setMessage('Code Not Found');
    $flash->setType('danger');
    $flash->setBody([
        'The code you entered could not be found in the system.',
        'Double-check your code, then <a href="index.php">go back</a> and try again.',
        'If the problem persists, please contact your coach.'
    ]);
} else {
    $cGetId = $db->prepare('SELECT c_id FROM contestants WHERE CONCAT(c_code, LEFT(c_firstname, 1), LEFT(c_lastname, 1)) = ?');
    $cGetId->execute([$drawCode]);
    $contestantId = $cGetId->fetchColumn();
    $cGetId->closeCursor();
    $cInRound = $db->prepare('SELECT COUNT(*) FROM draws WHERE d_round = ? AND d_contestant = ?');
    $cInRound->execute([$drawRound, $contestantId]);
    if ($cInRound->fetchColumn() == 0) {
        $flash->setMessage('Contestant Not in Round');
        $flash->setType('danger');
        $flash->setBody([
            'The code you entered did not match anyone scheduled in this round.',
            'Please double-check your code. If it\'s early, try again later.',
            'If the problem persists, contact your coach'
        ]);
    }
    $cInRound->closeCursor();
}
$cValidate->closeCursor();

if (!$flash->isOk()) include TPL . '/flash_and_exit.html.php';

// Determine whether it's too early or late to show the questions.


// Fetch and display the questions.


// Display a time warning if necessary.

