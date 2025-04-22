<?php
session_start(); // Avvia la sessione per gestire l'accesso utente
require_once 'config.php'; // Include la connessione al database

// Se l'utente non è loggato, lo reindirizza al login
if (!isset($_SESSION['loggato'])) {
  header("Location: login.php");
  exit;
}

$username = $_SESSION['username']; // Recupera lo username dalla sessione

// Recupera i dati dell'utente loggato
$stmt = $pdo->prepare("SELECT * FROM utenti WHERE username = ?");
$stmt->execute([$username]);
$utente = $stmt->fetch();

// Gestione modifica progetto (admin)
if (isset($_POST['modifica_progetto']) && $utente['ruolo'] === 'admin') {
  $id = (int) $_POST['edit_id'];
  $titolo = trim($_POST['edit_titolo']);
  $descrizione = trim($_POST['edit_descrizione']);
  $descrizione_lista = trim($_POST['edit_descrizione_lista']);
  $data_realizzazione = $_POST['edit_data_realizzazione'] ?: null;
  $nome_cliente = trim($_POST['edit_nome_cliente']);
  $tecnologie = trim($_POST['edit_tecnologie']);

  $query = "UPDATE progetti SET titolo = ?, descrizione = ?, descrizione_lista = ?, data_realizzazione = ?, nome_cliente = ?, tecnologie = ?";
  $params = [$titolo, $descrizione, $descrizione_lista, $data_realizzazione, $nome_cliente, $tecnologie];

  if (!empty($_FILES['edit_immagine']['name'])) {
    $fileName = basename($_FILES['edit_immagine']['name']);
    $uploadPath = 'img/' . $fileName;
    move_uploaded_file($_FILES['edit_immagine']['tmp_name'], $uploadPath);
    $query .= ", immagine = ?";
    $params[] = $uploadPath;
  }

  $query .= " WHERE id = ?";
  $params[] = $id;

  $update = $pdo->prepare($query);
  $update->execute($params);
}


// Se l'admin ha richiesto l'eliminazione di un progetto
if (isset($_POST['delete_project']) && $utente['ruolo'] === 'admin') {
  $del = $pdo->prepare("DELETE FROM progetti WHERE id = ?");
  $del->execute([$_POST['delete_project']]);
}

// Se l'admin ha richiesto l'eliminazione di un messaggio dal form contatti
if (isset($_POST['delete_contact']) && $utente['ruolo'] === 'admin') {
  $del = $pdo->prepare("DELETE FROM contatti WHERE id = ?");
  $del->execute([$_POST['delete_contact']]);
}

// Se l'admin ha richiesto l'eliminazione di un utente (ma non admin)
if (isset($_POST['delete_user']) && $utente['ruolo'] === 'admin') {
  $get = $pdo->prepare("SELECT ruolo FROM utenti WHERE id = ?");
  $get->execute([$_POST['delete_user']]);
  $row = $get->fetch();
  if ($row && $row['ruolo'] !== 'admin') {
    $pdo->prepare("DELETE FROM utenti WHERE id = ?")->execute([$_POST['delete_user']]);
  }
}

// Recupera tutti i progetti dal database
$progetti = $pdo->query("SELECT * FROM progetti")->fetchAll();

// Recupera tutti i messaggi di contatto ordinati per data
$contatti = $pdo->query("SELECT * FROM contatti ORDER BY data_invio DESC")->fetchAll();

// Se l'utente è admin, recupera l'elenco completo degli utenti
$utenti = [];
if ($utente['ruolo'] === 'admin') {
  $utenti = $pdo->query("SELECT id, username, nome, cognome, email, ruolo FROM utenti ORDER BY id DESC")->fetchAll();
}

// Inizializza variabili per mostrare messaggi di conferma o errore nell'inserimento utente
$successoInserimento = "";
$erroreInserimento = "";

// Se l'admin invia il form per creare un nuovo utente
if (isset($_POST['new_username']) && $utente['ruolo'] === 'admin') {
  $newUsername = trim($_POST['new_username']);
  $newNome = trim($_POST['new_nome']);
  $newCognome = trim($_POST['new_cognome']);
  $newEmail = trim($_POST['new_email']);
  $newPassword = $_POST['new_password'];
  $newRuolo = $_POST['new_ruolo'];

  // Verifica se username o email sono già registrati
  $check = $pdo->prepare("SELECT * FROM utenti WHERE username = ? OR email = ?");
  $check->execute([$newUsername, $newEmail]);

  if ($check->rowCount() > 0) {
    $erroreInserimento = "Username o email già registrati.";
  } else {
    // Cifra la password e inserisce il nuovo utente nel database
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    $insert = $pdo->prepare("INSERT INTO utenti (username, nome, cognome, email, password, ruolo) VALUES (?, ?, ?, ?, ?, ?)");
    $insert->execute([$newUsername, $newNome, $newCognome, $newEmail, $hashedPassword, $newRuolo]);
    $successoInserimento = "✅ Utente creato correttamente!";
  }
}
?>

<?php $pageTitle = "Area Riservata - Alessio Cattaneo";
include('header.php'); ?>

<body>
  <div class="wrapper">
    <main class="area-riservata">
      <section class="container">

        <!-- Saluto utente e mostra i suoi dati -->
        <h1>👋 Ciao <?= htmlspecialchars($utente['nome']) ?> (<?= $utente['username'] ?>)</h1>
        <p>Ruolo: <strong><?= htmlspecialchars($utente['ruolo']) ?></strong> | Email:
          <?= htmlspecialchars($utente['email']) ?>
        </p>
        <a href="logout.php" class="logout-btn">Logout</a>

        <hr>

        <!-- Sezione Progetti -->
        <h2>📂 Progetti</h2>
        <?php if ($progetti):
          foreach ($progetti as $progetto): ?>
            <div class="card">
              <h3><?= htmlspecialchars($progetto['titolo']) ?></h3>
              <p><?= nl2br(htmlspecialchars($progetto['descrizione'])) ?></p>

              <?php if ($utente['ruolo'] === 'admin'): ?>
                <form method="post" style="display:inline;">
                  <button type="submit" name="delete_project" value="<?= $progetto['id'] ?>"
                    title="Elimina progetto">–</button>
                </form>

                <button type="button" onclick="document.getElementById('edit-<?= $progetto['id'] ?>').style.display='block'"
                  title="Modifica progetto">✎</button>

                <form method="post" enctype="multipart/form-data" style="display:none; margin-top: 1rem;"
                  id="edit-<?= $progetto['id'] ?>">
                  <input type="hidden" name="edit_id" value="<?= $progetto['id'] ?>">

                  <div class="form-group">
                    <label>titolo</label>
                    <input type="text" name="edit_titolo" value="<?= htmlspecialchars($progetto['titolo']) ?>"
                      placeholder="Titolo" required>
                  </div>

                  <div class="form-group">
                    <label>descrizione</label>
                    <textarea name="edit_descrizione" placeholder="Descrizione lunga"
                      required><?= htmlspecialchars($progetto['descrizione']) ?></textarea>
                  </div>

                  <div class="form-group">
                    <label>descrizione_lista</label>
                    <textarea name="edit_descrizione_lista"
                      placeholder="Descrizione breve"><?= htmlspecialchars($progetto['descrizione_lista'] ?? '') ?></textarea>
                  </div>

                  <div class="form-group">
                    <label>data_realizzazione</label>
                    <input type="date" name="edit_data_realizzazione"
                      value="<?= htmlspecialchars($progetto['data_realizzazione'] ?? '') ?>">
                  </div>

                  <div class="form-group">
                    <label>nome_cliente</label>
                    <input type="text" name="edit_nome_cliente"
                      value="<?= htmlspecialchars($progetto['nome_cliente'] ?? '') ?>">
                  </div>

                  <div class="form-group">
                    <label>tecnologie</label>
                    <textarea name="edit_tecnologie"
                      placeholder="HTML, CSS, JavaScript, PHP..."><?= htmlspecialchars($progetto['tecnologie'] ?? '') ?></textarea>
                  </div>

                  <div class="form-group">
                    <label>immagine</label>
                    <input type="file" name="edit_immagine" accept="image/*">
                  </div>

                  <div class="form-actions">
                    <button type="submit" name="modifica_progetto">Salva</button>
                    <button type="button" class="annulla" onclick="this.closest('form').style.display='none'">Annulla</button>
                  </div>
                </form>

              <?php endif; ?>
            </div>
          <?php endforeach; else: ?>
          <p>Nessun progetto presente.</p>
        <?php endif; ?>

        <hr>

        <!-- Sezione Messaggi dal form contatti -->
        <h2>📩 Messaggi contatto</h2>
        <?php if ($contatti):
          foreach ($contatti as $msg): ?>
            <div class="card">
              <p><strong><?= htmlspecialchars($msg['nome']) ?>     <?= htmlspecialchars($msg['cognome']) ?></strong> -
                <?= htmlspecialchars($msg['email']) ?>
              </p>
              <p><em>Oggetto:</em> <?= htmlspecialchars($msg['oggetto']) ?></p>
              <p><?= nl2br(htmlspecialchars($msg['messaggio'])) ?></p>
              <small>Inviato il: <?= $msg['data_invio'] ?></small>
              <?php if ($utente['ruolo'] === 'admin'): ?>
                <!-- Tasto elimina messaggio (solo admin) -->
                <form method="post" style="display:inline;">
                  <button type="submit" name="delete_contact" value="<?= $msg['id'] ?>" title="Elimina messaggio">–</button>
                </form>
              <?php endif; ?>
            </div>
          <?php endforeach; else: ?>
          <p>Nessun messaggio ricevuto.</p>
        <?php endif; ?>

        <?php if ($utente['ruolo'] === 'admin'): ?>
          <hr>
          <!-- Form per creare un nuovo utente -->
          <h2>👤 Aggiungi nuovo utente</h2>

          <?php if ($successoInserimento): ?>
            <p style="color: #0f0;"><?= $successoInserimento ?></p><?php endif; ?>
          <?php if ($erroreInserimento): ?>
            <p style="color: red;"><?= $erroreInserimento ?></p><?php endif; ?>

          <form method="post">
            <div class="form-group"><input type="text" name="new_username" placeholder="Username" required></div>
            <div class="form-group"><input type="text" name="new_nome" placeholder="Nome" required></div>
            <div class="form-group"><input type="text" name="new_cognome" placeholder="Cognome" required></div>
            <div class="form-group"><input type="email" name="new_email" placeholder="Email" required></div>
            <div class="form-group"><input type="password" name="new_password" placeholder="Password" required></div>
            <div class="form-group">
              <select name="new_ruolo" required>
                <option value="utente">Utente</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <button type="submit">Crea utente</button>
          </form>

          <hr>
          <!-- Elenco utenti già presenti -->
          <h2>👥 Elenco utenti</h2>
          <?php foreach ($utenti as $u): ?>
            <div class="card">
              <strong><?= $u['username'] ?></strong> (<?= $u['ruolo'] ?>) - <?= $u['email'] ?>
              <br><?= $u['nome'] ?>     <?= $u['cognome'] ?>
              <?php if ($u['ruolo'] !== 'admin'): ?>
                <!-- Tasto elimina utente (non admin) -->
                <form method="post" style="display:inline;">
                  <button type="submit" name="delete_user" value="<?= $u['id'] ?>" title="Elimina utente">–</button>
                </form>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </section>
    </main>
    <?php include('footer.php'); ?>
  </div>
</body>