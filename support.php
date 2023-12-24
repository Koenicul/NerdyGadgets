<?php

include __DIR__ . "/header.php";

?>
<link rel="stylesheet" type="text/css" href="help.css">
<div class="trans">
        <form method="post" name="emailform" action="supportsubmit.php">
            <label for="chk" aria-hidden="true">Support</label>
            <input class="login1" type="text" placeholder="Naam" id="emailName" name="Name" required>
            <input class="login1" type="text" placeholder="Email" id="emailFrom" name="Email" required>
            <input class="login1" type="text" placeholder="Onderwerp" id="emailTopic" name="Topic" required>
            <textarea class="login1 center textarea" id="emailDescription" placeholder="Beschrijf het probleem" name="emailDescription" rows="4" cols="37" required></textarea>
            <br>
            <input class="button3" type="submit" value="Versturen">
        </form>
</div>

