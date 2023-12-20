<?php

include __DIR__ . "/header.php";

$name = $_POST['emailName'];
$email = $_POST['emailFrom'];
$topic = $_POST['emailTopic'];
$description = $_POST['emailDescription'];

$email_to = 'joshuadh@live.nl';
$email_subject = ("New Support Ticket: ".$topic);
$email_body = ("A new support ticket has been filled out by ".$name."\n. Here is the message:\n ".$description);

$email_headers = "From: ".$email."\r\n";

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
    echo ("Verzonden van: $email | Verzonden naar: $email_to | Onderwerp: $email_subject | Mail: $email_body | ");
    ?>
</div>

</body>
</html>
