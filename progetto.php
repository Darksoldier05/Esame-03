<?php
// Importa il file di configurazione, che include la connessione al database
require_once 'config.php';

// Ottiene l'ID del progetto dalla query string e lo forza a intero
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Prepara una query per recuperare il progetto corrispondente all'ID
$stmt = $pdo->prepare("SELECT * FROM progetti WHERE id = ?");
$stmt->execute([$id]);
$progetto = $stmt->fetch(); // Ottiene il progetto come array associativo

// Se non è stato trovato nessun progetto con quell'ID, mostra errore e termina
if (!$progetto) {
  die("❌ Progetto non trovato.");
}

// Imposta il titolo della pagina con il titolo del progetto (sanificato)
$pageTitle = htmlspecialchars($progetto['titolo']);
include('header.php'); // Include l'header della pagina
?>

<body>
  <div class="wrapper"> <!-- Wrapper per struttura flex tra contenuto e footer -->
    <main class="progetto-singolo"> <!-- Contenuto principale della pagina -->
      <section class="container"> <!-- Container centrale del contenuto -->

        <h1><?= htmlspecialchars($progetto['titolo']) ?></h1> <!-- Titolo del progetto -->

        <?php if (!empty($progetto['immagine'])): ?> <!-- Se è presente un'immagine del progetto -->
          <img src="<?= htmlspecialchars($progetto['immagine']) ?>" alt="Immagine progetto" class="img-progetto">
        <?php endif; ?>

        <p><?= nl2br(htmlspecialchars($progetto['descrizione'])) ?></p> <!-- Descrizione del progetto (con a capo convertiti) -->

        <a href="progetti.php" class="back-link">← Torna ai progetti</a> <!-- Link per tornare all'elenco progetti -->

      </section>
    </main>
    <?php include('footer.php'); ?> <!-- Include il footer -->
  </div>
</body>
