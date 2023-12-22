<?php
include __DIR__ . "/header.php";
require_once('cartfuncties.php');

$database = new Database();

$authentication = new Authentication($database->connection);

$errors = array();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['username'])) {
        array_push($errors, "Geen gebruikersnaam ingevuld");
    }

    if (empty($_POST['emails'])) {
        array_push($errors, "Geen volledige email ingevuld");
    }
    if (empty($_POST['postalcode'])) {
        array_push($errors, "Geen postcode ingevuld");
    }
    if (empty($_POST['houseNumber'])) {
        array_push($errors, "Geen huisnummer ingevuld");
    }
    if (empty($_POST['password_new'])) {
        array_push($errors, "Geen nieuw Wachtwoord ingevuld");
    }

    if (empty($_POST['password_new_check'])) {
        array_push($errors, "Geen controle Wachtwoord ingevuld");
    }

    if ($_POST['password_new'] != $_POST['password_new_check']) {
        array_push($errors, "Ingevulde (nieuwe) wachtwoorden komen niet overeen");
    }
    if (count($errors) == 0) {
        $user = $authentication->getUser($_POST['emails']);
        if (!$user) {
            $insertGelukt = $authentication->addUser($_POST['username'], $_POST['emails'], $_POST['password_new'], $_POST['postalcode'], $_POST['houseNumber'] );
            if ($insertGelukt) {
                header("Location: login.php");
                exit;
            } else {
                array_push($errors, "Niet gelukt probeer het later opnieuw");
            }
        } else {
            array_push($errors, "De Email word al gebruikt");
        }
    }

}
?>
<link rel="stylesheet" type="text/css" href="help.css">
<div class="trans">
    <input type="checkbox" id="chk" aria-hidden="true">

    <div class="signup">
        <form action="aanmelden.php" method="post">
            <label for="chk" aria-hidden="true">Aanmelden</label>
            <input class="login1" type="text" name="username" placeholder="Gebruikersnaam">
            <input class="login1" type="email" name="emails" placeholder="Email">
            <input class="login1" type="text" name="postalcode" placeholder="Postcode">
            <input class="login1" type="text" name="houseNumber" placeholder="Huisnummer">
            <input class="login1" type="password" name="password_new" placeholder="Wachtwoord">
            <input class="login1" type="password" name="password_new_check" placeholder="Controle wachtwoord">
            <input class="button3" type="submit" name="submit" value="Aanmelden">
        </form>
    </div>
</div>

<?php

if (count($errors) > 0) {
    echo '<ul>';
    foreach ($errors as $error) {
        echo '<li>' . $error . '</li>';
    }
    echo '</ul>';
}
?>
