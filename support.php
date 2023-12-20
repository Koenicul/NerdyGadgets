<?php

include __DIR__ . "/header.php";

?>
<html>
<head>
    <title>Support</title>
<link rel="stylesheet" href="Public/CSS/supportCSS.css">

</head>
<body>

<h2>Support</h2>
<div id="CenteredContent">
    <div class="center col-4">
        <form method="post" name="emailform" action="supportsubmit.php">
            <label for="emailName">Vul uw naam in:</label><br>
            <input type="text" id="emailName" name="emailName"><br>
            <label for="emailFrom">Vul uw mailadres in:</label><br>
            <input type="text" id="emailFrom" name="emailFrom"><br>
            <label for="emailTopic">Onderwerp Probleem:</label><br>
            <input type="text" id="emailTopic" name="emailTopic"><br>
            <label for="emailDescription">Leg uw probleem uit:</label><br>
            <textarea id="emailDescription" name="emailDescription" rows="4" cols="30"></textarea><br>
            <input type="submit" value="Versturen">
        </form>
    </div>
</div>
</body>
</html>
