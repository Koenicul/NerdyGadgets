<?php

include __DIR__ . "/header.php";

$send = FALSE;
if (isset($_POST['Name'])) {
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $topic = $_POST['Topic'];
    $description = $_POST['emailDescription'];
}


if (isset($_POST['Name']) &&  $_POST['Email'] && $_POST['Topic'] && $_POST['emailDescription']) {
    $send = TRUE;
    postTicket($databaseConnection, $email, $topic, $description, $name);
}

//mail($email_to, $email_subject, $email_body, $email_headers);

?>
<html>
<head>
    <title>Support</title>
    <link rel="stylesheet" href="Public/CSS/supportCSS.css">

</head>
<body>

<h2>Support</h2>

<div class="center">
    <p>Bedankt voor uw vraag. We gaan ermee bezig!</p>
    <?php
    if ($send) {
        echo("Het antwoord op uw vraag zal zo snel mogelijk behandeld worden. Het antwoord zal gestuurd worden naar $email.");
    }else{
        echo("Er is geen vraag gevonden.");
    }
    ?>
</div>

</body>
</html>