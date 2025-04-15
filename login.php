<?php
session_start(); // Avvia la sessione PHP per gestire il login
require_once 'config.php'; // Include la configurazione del database

$errore = ""; // Inizializza una variabile per gestire eventuali messaggi di errore

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Controlla se il form è stato inviato con metodo POST
  $username = trim($_POST['username'] ?? ''); // Recupera lo username dal form, rimuovendo spazi vuoti
  $password = $_POST['password'] ?? ''; // Recupera la password dal form

  // Recupera l'utente dal database usando lo username
  $stmt = $pdo->prepare("SELECT * FROM utenti WHERE username = ?");
  $stmt->execute([$username]);
  $utente = $stmt->fetch(); // Ottiene i dati dell'utente come array associativo

  // Verifica se l'utente esiste e se la password è corretta
  if ($utente && password_verify($password, $utente['password'])) {
    $_SESSION['loggato'] = true; // Imposta la sessione come autenticata
    $_SESSION['username'] = $utente['username']; // Salva lo username nella sessione
    header("Location: area-riservata.php"); // Reindirizza all'area riservata
    exit; // Termina lo script dopo il redirect
  } else {
    $errore = "Credenziali errate."; // Messaggio di errore in caso di login fallito
  }
}
?>

<?php $pageTitle = "Login - Alessio Cattaneo"; include('header.php'); ?> <!-- Imposta il titolo della pagina e include l'header -->

<body>
  <div class="wrapper"> <!-- Wrapper principale -->
    <main>
      <section class="login-page"> <!-- Sezione con stile dedicato alla pagina login -->
        <div class="container">
          <form method="post"> <!-- Form per l'autenticazione -->
            <h2>Accesso riservato</h2>

            <?php if ($errore): ?>
              <p style="color: red; text-align: center;"><?= $errore ?></p> <!-- Mostra il messaggio di errore -->
            <?php endif; ?>

            <div class="form-group">
              <input type="text" name="username" placeholder="Username" required> <!-- Campo username -->
            </div>

            <div class="form-group password-toggle">
              <input type="password" name="password" id="password" placeholder="Password" required> <!-- Campo password -->
              <span class="toggle-password" onclick="togglePassword()">👁️</span> <!-- Icona per mostra/nascondi password -->
            </div>

            <button type="submit">Accedi</button> <!-- Pulsante invio -->
          </form>
        </div>
      </section>
    </main>

    <?php include('footer.php'); ?> <!-- Inclusione del footer -->
  </div>

  <script>
    function togglePassword() {
      const password = document.getElementById("password"); // Seleziona il campo password
      const icon = document.querySelector(".toggle-password"); // Seleziona l'icona occhio
      if (password.type === "password") {
        password.type = "text"; // Mostra la password in chiaro
        icon.textContent = "🙈"; // Cambia icona
      } else {
        password.type = "password"; // Nasconde la password
        icon.textContent = "👁️"; // Ripristina icona
      }
    }
  </script>
</body>
