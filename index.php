<?php
require_once 'app/init.php';

require_once TPL . '/header.html.php';
?>
    <div class="row">
        <div class="col">
            <h1 class="display-2">FGCCFL Extemp Draw</h1>
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-8">
            <h3>Contestants</h3>
            <form action="draw.php" method="get">
                <label for="roundSelect" class="form-label">Round</label>
                <select name="t" id="roundSelect" class="form-select mb-3" aria-label="Round selector">
                    <?php include TPL . '/active_rounds.html.php'; ?>
                </select>
                <div class="mb-3">
                    <label for="drawCode" class="form-label">Speaker Code and Initials</label>
                    <input type="text" class="form-control" id="drawCode" placeholder="X000AB" aria-describedby="drawCodeGuidance">
                    <div id="drawCodeGuidance" class="form-text">
                        Enter your speaker code (from Tabroom) followed by your first and last initials.
                        Don't enter spaces or punctuation.
                    </div>
                </div>
                <button type="submit" class="btn btn-lg btn-primary">Draw</button>
            </form>
        </div>
        <div class="col-4">
            <h3>Judges</h3>
            <p><a href="judges.php">Click here</a> to access questions.</p>
        </div>
    </div>


<?php
require_once TPL . '/footer.html.php';
require_once APP . '/finish.php';