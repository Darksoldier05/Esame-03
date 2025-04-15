<?php
// Includiamo il file di configurazione contenente la connessione al database (oggetto $pdo)
require_once 'config.php';

// Definiamo il titolo della pagina, utile per il tag <title> nel layout HTML
$pageTitle = "Progetti - Alessio Cattaneo";

// Recuperiamo tutti i progetti dal database, ordinati per ID decrescente (dal più recente)
$progetti = $pdo->query("SELECT * FROM progetti ORDER BY id DESC")->fetchAll();

// Includiamo l'header comune del sito (contiene doctype, head, menu ecc.)
include('header.php');
?>

<body>
  <div class="wrapper">
    <main class="progetti-pubblici">
      <section class="container">
        <h1>I miei progetti</h1>

        <?php if ($progetti): ?>
          <!-- Se ci sono progetti, mostriamo una griglia di card -->
          <div class="griglia-progetti">
            <?php foreach ($progetti as $progetto): ?>
              <!-- Ogni progetto viene mostrato come una card cliccabile -->
              <div class="card-progetto">
                <a href="progetto.php?id=<?= $progetto['id'] ?>" class="project-link">
                  <!-- Titolo del progetto -->
                  <h3><?= htmlspecialchars($progetto['titolo']) ?></h3>
                  <!-- Mostriamo descrizione breve se presente, altrimenti un estratto dei primi 100 caratteri -->
                  <p><?= htmlspecialchars($progetto['descrizione_lista']) ?: substr(strip_tags($progetto['descrizione']), 0, 100) . '...' ?></p>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <!-- Se non ci sono progetti nel database -->
          <p>Nessun progetto disponibile al momento.</p>
        <?php endif; ?>
      </section>
    </main>
    <!-- Includiamo il footer comune del sito -->
    <?php include('footer.php'); ?>
  </div>
</body>
