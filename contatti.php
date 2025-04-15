<?php
require_once 'config.php'; // Connessione al database

// Se il form viene inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Prendi i dati e pulisci gli input
  $nome = trim($_POST['nome']);
  $cognome = trim($_POST['cognome']);
  $email = trim($_POST['email']);
  $oggetto = trim($_POST['oggetto']);
  $messaggio = trim($_POST['messaggio']);

  // Inserisci nel database
  $stmt = $pdo->prepare("INSERT INTO contatti (nome, cognome, email, oggetto, messaggio, data_invio) VALUES (?, ?, ?, ?, ?, NOW())");
  $stmt->execute([$nome, $cognome, $email, $oggetto, $messaggio]);

  // Reindirizza per evitare doppio invio
  header("Location: contatti.php?inviato=1");
  exit;
}
?>

<?php $pageTitle = "Contatti - Alessio Cattaneo"; include('header.php'); ?>

<header>
  <div class="container">
    <h1>Contattami</h1>
    <p>Hai una domanda, una proposta o vuoi solo fare due chiacchiere?</p>
  </div>
</header>

<main>
  <section class="contatti">
    <div class="container">

      <!-- Messaggio di conferma -->
      <?php if (isset($_GET['inviato'])): ?>
        <p class="success-msg" style="color: #0f0; text-align: center; font-weight: bold; margin-bottom: 1rem;">
          ✅ Messaggio inviato con successo!
        </p>
      <?php endif; ?>

      <!-- Form contatti -->
      <form method="post" action="#">
        <div class="form-group">
          <input type="text" name="nome" placeholder="Nome *" required>
        </div>
        <div class="form-group">
          <input type="text" name="cognome" placeholder="Cognome *" required>
        </div>
        <div class="form-group">
          <input type="email" name="email" placeholder="Email *" required>
        </div>
        <div class="form-group">
          <input type="text" name="oggetto" placeholder="Oggetto *" required>
        </div>
        <div class="form-group">
          <textarea name="messaggio" placeholder="Scrivi il tuo messaggio... *" required></textarea>
        </div>
        <button type="submit">Invia messaggio</button>
      </form>
    </div>
  </section>
</main>

<?php include('footer.php'); ?>
