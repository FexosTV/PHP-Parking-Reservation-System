<?php
$servername = '';          // Názov servera kde je databaza
$username = '';             // Prihlasovacie meno do databazy
$password = '';         // Heslo na prihlasenie do databazy pomocou usera
$db = '';               // Názov databazy


    // Spojenie s databazou
    $auth = mysqli_connect($servername, $username, $password,$db);

    // Kontrola spojenia s databazou
    if (!$auth) {
        die("Connection failed: " . mysqli_connect_error());
    }
?>