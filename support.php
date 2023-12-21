<?php

include __DIR__ . "/header.php";

?>
<link rel="stylesheet" type="text/css" href="help.css">
<div class="trans">
        <form method="post" name="emailform" action="supportsubmit.php">
            <label for="chk" aria-hidden="true">Support</label>
            <input class="login1" type="text" placeholder="Naam" id="emailName" name="emailName" required>
            <input class="login1" type="text" placeholder="Email" id="emailFrom" name="emailFrom" required>
            <input class="login1" type="text" placeholder="Onderwerp" id="emailTopic" name="emailTopic" required>
            <textarea class="login1 center" style="margin: auto; resize= none;" id="emailDescription" placeholder="Beschrijf het probleem" name="emailDescription" rows="4" cols="35" required></textarea>
            <br>
            <input class="button3" type="submit" value="Versturen">
        </form>
</div>

