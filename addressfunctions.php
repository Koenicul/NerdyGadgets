<?php
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function GetAddress($postalcode, $houseNumber)
{
    $postalcode = str_replace(" ", "", $postalcode);
    $api_key = $_ENV["TOKEN"];
    $url = "https://json.api-postcode.nl?postcode=". $postalcode ."&number=" . $houseNumber;
    $ch = curl_init($url);
    $headers = array(
        'token: ' . $api_key
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $response = json_decode($response, true);

    curl_close($ch);

    if (!array_key_exists('error', $response)) {
        return $response;
    }

    echo '<script>alert("Geen adres gevonden.")</script>';
}

function saveUser($user) {
    $_SESSION['user'] = $user;
}

function getUser() {
    if (isset($_SESSION['user'])) {               //controleren of winkelmandje (=cart) al bestaat
        $user = $_SESSION['user'];                  //zo ja:  ophalen
    } else {
        $user = array();                            //zo nee: dan een nieuwe (nog lege) array
    }
    return $user;        
}

function validateForm($form) {
    if (!isset($form['name']) && !isset($form['postalcode']) && !isset($form['houseNumber'])) {
        return false;
    }
            
    if ($form['name'] == "" && $form['postalcode'] == "" && $form['houseNumber'] == "") {
        return false;
    }
    return true;
}

function getUserAddress($user) {
    $str = $user["street"] . " " . $user["house_number"] . ", " . $user["city"];
    return $str;
}