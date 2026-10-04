<?php

/** @var string $error */

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title>Erreur</title>

    <link
      rel="stylesheet"
      href="assets/style/app.css"
    >
  </head>

  <body class='page-message'>

    <h1>Une erreur est survenue</h1>

    <div class="error">
      <?= htmlspecialchars($error) ?>
    </div>

    <p>
      <a href="?action=create-reservation">
        Retour à la réservation
      </a>

      <a href="?action=parkings">
        Retour à la carte
      </a>
    </p>

  </body>
</html>