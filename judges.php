<?php
require_once 'app/init.php';

if (isset($_POST['goRetrieve'])) include APP . '/questions.php';

require_once TPL . '/header.html.php';
?>
<div class="row">
    <div class="col">
        <h1 class="display-2">FGCCFL Extemp Draw</h1>
        <hr>
    </div>
</div>
<div class="row">
    <div class="col">
        <h3>Judges</h3>
        <form action="judges.php" method="post">
            <div class="mb-3">
                <label for="judgePin" class="form-label">PIN (from blast message)</label>
                <input type="number" class="form-control form-control-lg" id="judgePin"
                       name="pin" placeholder="000000" maxlength="6">
                <input type="hidden" name="goRetrieve" value="1">
            </div>
            <button type="submit" class="btn btn-lg btn-success">Get Questions</button>
        </form>
    </div>
</div>
<div class="row">
    <div class="col">
        <!-- TODO: Create the code to dump the questions. -->
    </div>
</div>