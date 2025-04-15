<!DOCTYPE html> <!-- Dichiara il tipo di documento HTML5 -->
<html lang="it"> <!-- Imposta la lingua del documento in italiano -->
<head>
  <meta charset="UTF-8" /> <!-- Specifica la codifica dei caratteri come UTF-8 -->
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/> <!-- Rende il layout responsive sui dispositivi mobili -->
  <title><?= isset($pageTitle) ? $pageTitle : 'Alessio Cattaneo' ?></title> <!-- Imposta il titolo della pagina dinamicamente se definito, altrimenti usa quello di default -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet"> <!-- Importa il font Inter da Google Fonts -->
  <link rel="stylesheet" href="css/Style.css" /> <!-- Collega il file CSS esterno per lo stile del sito -->
</head>
<body>

  <nav class="navbar"> <!-- Inizio della barra di navigazione -->
  <a href="login.php" class="logo" title="Accedi all'area riservata">AlessioDev</a> <!-- Logo che funge da link alla pagina di login -->
    <div class="menu-toggle" onclick="toggleMenu()">☰</div> <!-- Bottone menu per la versione mobile -->
    <ul id="navLinks"> <!-- Elenco dei link di navigazione -->
      <li>
        <a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : '' ?>" title="Vai alla Home">Home</a> <!-- Link alla Home con classe 'active' se siamo nella pagina Home -->
      </li>
      <li>
        <a href="progetti.php" class="<?= basename($_SERVER['PHP_SELF']) === 'progetti.php' ? 'active' : '' ?>" title="Vai alla pagina dei Progetti">Progetti</a> <!-- Link alla pagina Progetti con classe 'active' se siamo su quella pagina -->
      </li>
      <li>
        <a href="chi-sono.php" class="<?= basename($_SERVER['PHP_SELF']) === 'chi-sono.php' ? 'active' : '' ?>" title="Scopri chi sono">Chi sono</a> <!-- Link alla pagina Chi sono con classe 'active' se attiva -->
      </li>
      <li>
        <a href="contatti.php" class="<?= basename($_SERVER['PHP_SELF']) === 'contatti.php' ? 'active' : '' ?>" title="Vai alla pagina di Contatto">Contatti</a> <!-- Link alla pagina Contatti con classe 'active' dinamica -->
      </li>
    </ul>
  </nav> <!-- Fine della barra di navigazione -->
