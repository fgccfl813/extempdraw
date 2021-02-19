<?php

$db = Database::connect()->open();

$pin = filter_input(INPUT_POST, 'pin', FILTER_SANITIZE_NUMBER_INT);

$verifyRoundSelect = "SELECT COUNT(r.r_id)";
$getRoundSelect = <<<ENDQUERY
SELECT r.r_id id, r.r_name name, t.t_name tourn, r.r_graphics img, r.r_legend legend
       UNIX_TIMESTAMP(r.r_firstdraw) draw 
ENDQUERY;
$roundFrom = <<<ENDQUERY
FROM rounds r JOIN tournaments t on r.r_tourn = t.t_id 
WHERE r.r_pin = ? AND t.t_start < NOW() AND t.t_end > NOW()
ENDQUERY;

$verifyRound = $db->prepare("$verifyRoundSelect $roundFrom");
$verifyRound->execute([$pin]);
if ($verifyRound->fetchColumn() == 0) {
    $flash->setType('danger');
    $flash->setMessage('Invalid PIN');
    $flash->addBody('Either the PIN is incorrect or the tournament is not in progress.');
} else {
    $getRound = $db->prepare("$getRoundSelect $roundFrom");
    $getRound->execute([$pin]);
    $theRound = $getRound->fetch(PDO::FETCH_OBJ);
    $getRound->closeCursor();
    if ($theRound->draw + (25*60) > time()) {
        $questionsAvailable = $theRound->draw + (25*60) + 10800;
        $flash->setType('warning');
        $flash->setMessage('Please try again');
        $flash->addBody("The questions in {$theRound->name} are not yet available.");
        $flash->addBody('Please try again at <strong>' . date('g:i A', $questionsAvailable) . '</strong> or later.');
    }
}
$verifyRound->closeCursor();
