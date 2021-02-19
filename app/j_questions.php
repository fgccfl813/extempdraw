<hr>
<h3><?= $theRound->name ?></h3>
<?php

$db = Database::connect()->open();

$getAllQuestionsQuery = <<<ENDQUERY
SELECT q_slot speaker, q_opt1 q1, q_opt2 q2, q_opt3 q3 
FROM questions WHERE q_round = :rd ORDER BY q_slot ASC
ENDQUERY;

$getAllQuestions = $db->prepare($getAllQuestionsQuery);
$getAllQuestions->bindValue(':rd', $theRound->id, PDO::PARAM_INT);
$getAllQuestions->execute();

while ($r = $getAllQuestions->fetch(PDO::FETCH_OBJ)) {
    $theQuestions = [$r->q1, $r->q2, $r->q3];
    echo "<h4>{$ordinal[$r->speaker]} Speaker</h4>\n";
    if ($theRound->img) {
        include TPL . '/imageset.html.php';
    } else {
        include TPL . '/topicset.html.php';
    }
}
$getAllQuestions->closeCursor();