<?php

/** @var ReservationConfirmationViewModel $confirmation */

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title>Réservation confirmée</title>
    <link
      rel="stylesheet"
      href="assets/style/app.css"
    >
  </head>

  <body class='page-message'>

    <h1>Réservation confirmée 🎉</h1>

    <p>
      Parking :
      <?= htmlspecialchars($confirmation->parking) ?>
    </p>

    <p>
      Début :
      <?= htmlspecialchars($confirmation->start) ?>
    </p>

    <p>
      Fin :
      <?= htmlspecialchars($confirmation->end) ?>
    </p>

    <p>
      Prix :
      <strong>
        <?= htmlspecialchars($confirmation->price) ?>
      </strong>
    </p>

    <p>
      <a href="?action=parkings">
        Retour à la carte
      </a>
    </p>

    <p>
      <a href="?action=create-reservation">
        Faire une autre réservation
      </a>
    </p>

  </body>
</html>