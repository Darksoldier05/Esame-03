<footer>
  <!-- Footer principale del sito -->
  <div class="container footer-flex">
    <!-- Testo con copyright dinamico che mostra l'anno corrente -->
    <p>&copy; <?= date("Y") ?> Alessio Cattaneo – Tutti i diritti riservati</p>

    <!-- Collegamenti alle pagine di Privacy e Cookie Policy -->
    <ul class="footer-links">
      <li>
        <a href="privacy.php" title="Leggi la Privacy Policy del sito">Privacy Policy</a>
      </li>
      <li>
        <a href="cookie.php" title="Leggi la Cookie Policy e scopri come vengono usati i cookie">Cookie Policy</a>
      </li>
    </ul>
  </div>
</footer>

<!-- Script per aprire/chiudere il menu mobile -->
<script>
  function toggleMenu() {
    document.getElementById('navLinks').classList.toggle('show');
  }
</script>

</body>
</html>
