<?php
// Imposta il titolo della pagina e include l'header comune del sito
$pageTitle = "Home - Alessio Cattaneo";
include('header.php');
?>

<header>
  <div class="container">
    <h1>Alessio Cattaneo</h1>
    <p>Web Designer & Sviluppatore</p>
  </div>
</header>

<main>
  <!-- Sezione introduttiva con un messaggio di benvenuto -->
  <section class="hero">
    <div class="container">
      <h2>Benvenuto nel mio portfolio</h2>
      <p>Creo siti moderni, dinamici e visivamente accattivanti. Dai un'occhiata a cosa posso offrirti!</p>
    </div>
  </section>

  <!-- Sezione con 3 motivi per scegliere Alessio, in formato a card -->
  <section class="progetti" id="progetti">
    <div class="container">
      <h2>Perché scegliere me</h2>
      <div class="grid">
        <div class="card">
          <h3>Professionalità</h3>
          <p>Ogni progetto è curato nei minimi dettagli, con codice pulito e design coerente. Garantisco qualità e
            rispetto delle scadenze.</p>
        </div>
        <div class="card">
          <h3>Creatività</h3>
          <p>Siti personalizzati, animazioni moderne e soluzioni uniche. Ogni lavoro riflette l’identità del cliente e
            si distingue online.</p>
        </div>
        <div class="card">
          <h3>Competenze Tecniche</h3>
          <p>Esperienza in HTML, CSS/SCSS, JavaScript, PHP e Java. Gestione completa di hosting, dominio e
            ottimizzazione responsive.</p>
        </div>
      </div>
    </div>
  </section>
</main>

<?php
// Inclusione del footer comune alla fine della pagina
include('footer.php');
?>
