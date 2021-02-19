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
$slotGet = $db->prepare('SELECT d_slot FROM draws WHERE d_round = ? AND d_contestant = ?');
$slotGet->execute([$drawRound, $contestantId]);
$cSlot = $slotGet->fetchColumn();
$slotGet->closeCursor();

$rGetDataQuery = <<<ENDQUERY
SELECT UNIX_TIMESTAMP(r_firstdraw) firstDraw, r_interval*60 drawSpace, r_graphics img, r_legend legend
FROM rounds WHERE r_id = ?
ENDQUERY;
$rGetData = $db->prepare($rGetDataQuery);
$rGetData->execute([$drawRound]);
$theRound = $rGetData->fetch(PDO::FETCH_OBJ);
$theRound->id = $drawRound;
$rGetData->closeCursor();

$cDrawTime = $theRound->firstDraw + ($cSlot * $theRound->drawSpace);
$cDrawTimeDisplay = date('g:i A', $cDrawTime + 10800);

$cWarnTime = $cDrawTime + 25 * 60;
$cSpeakTime = $cDrawTime + 30 * 60;

$cEndTime = $theRound->firstDraw + 80 * 60;

if (time() < $cDrawTime) {
    $flash->setMessage('Draw Not Started');
    $flash->setType('danger');
    $flash->setBody([
        "Your draw is scheduled to start at <strong>{$cDrawTimeDisplay}</strong>.",
        'If this message seems irregular, check to ensure that you have the right round selected.',
        'This page will automatically reload every 30 seconds, but you can reload manually as well.'
    ]);
    $timeout = 30;
} elseif (time() > $cEndTime) {
    $flash->setMessage('Round Is Over');
    $flash->setType('danger');
    $flash->setBody([
        'This round has already ended.',
        'If this message seems irregular, check to ensure that you have the right round selected.',
    ]);
}

if (!$flash->isOk()) include TPL . '/flash_and_exit.html.php';


include TPL . '/header.html.php';
?>
<div class="row">
    <div class="col">
        <h1 class="display-2">FGCCFL Extemp Draw</h1>
        <hr>
    </div>
</div>
<div class="row">
    <div class="col">
        <h4><?= $ordinal[$cSlot] ?> Speaker</h4>
<?php
// Fetch and display the questions.
$qGet = $db->prepare('SELECT * FROM questions WHERE q_round = ? AND q_slot = ?');
$qGet->execute([$theRound->id, $cSlot]);
$tt = $qGet->fetch(PDO::FETCH_OBJ);
$qGet->closeCursor();

$theQuestions = [$tt->q_opt1, $tt->q_opt2, $tt->q_opt3];
if ($theRound->img) {
    include TPL . '/imageset.html.php';
} else {
    include TPL . '/topicset.html.php';
}

$timeout = 60;

// Display a time warning if necessary.
if (time() > $cWarnTime) {
    $timeUp = (time() > $cSpeakTime);
    $flash->setMessage(($timeUp) ? 'Time Has Expired' : 'Check Your Time');
    $flash->setType(($timeUp) ? 'warning' : 'info');
    $detail = ($timeUp) ? 'Please report to your NSDA Campus competition room.' : 'You have less than 5 minutes remaining.';
    $flash->setBody([$detail]);
    $flash->publish();
    $timeout = 30;
}

?>
    </div></div>
<?php
if (isset($timeout) && intval($timeout) > 0) include TPL . '/timeout.html.php';
require_once TPL . '/footer.html.php';
require_once APP . '/finish.php';
