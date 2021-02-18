<?php
$db = Database::connect()->open();

$getRoundsQuery = <<<ENDQUERY
SELECT r.r_id , CONCAT(t.t_name, ' - ', r.r_name) 
FROM rounds r JOIN tournaments t on r.r_tourn = t.t_id
WHERE t.t_start < NOW() AND t.t_end > NOW()
ENDQUERY;

$getRounds = $db->query($getRoundsQuery);
while ($r = $getRounds->fetch(PDO::FETCH_NUM)) {
    vprintf('<option value="%d">%s</option>' . "\n", $r);
}
$getRounds->closeCursor();