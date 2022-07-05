<?php
    // Zapnúť session
    session_start();

    // Zniciť session.
    session_destroy();

    // Presmerovať na hlavnú stránku
    header("location: ./");
?>