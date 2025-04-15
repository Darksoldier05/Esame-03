<?php
// Avvia la sessione per poterla poi distruggere.
// Questo script effettua il logout dell'utente rimuovendo tutti i dati di sessione.
// Dopo aver distrutto la sessione, reindirizza l'utente alla pagina di login.
// L'exit assicura che il resto dello script non venga eseguito.
session_start();
session_destroy();
header("Location: login.php");
exit;
?>
