<?php
// Definizione delle credenziali e parametri per la connessione al database
$host = '31.11.39.212';            // IP del server MySQL (fornito da Aruba)
$db   = 'Sql1856275_1';           // Nome del database da usare
$user = 'Sql1856275';             // Nome utente per accedere al database
$pass = 'Aleparis2507.';          // Password dell'utente del database

try {
  // Crea una nuova connessione PDO al database con charset UTF-8
  $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);

  // Imposta l'attributo di errore per lanciare eccezioni in caso di errore
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  // In caso di errore nella connessione, termina lo script e mostra il messaggio d'errore
  die("❌ Connessione fallita: " . $e->getMessage());
}