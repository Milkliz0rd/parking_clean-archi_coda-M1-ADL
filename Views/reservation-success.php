<?php

/** @var CreateReservationResultDTO $result */

$priceInEuros = $result->priceInCents / 100;

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
      #<?= $result->parkingId ?>
    </p>

    <p>
      Début :
      <?= date(
        'd/m/Y H:i',
        $result->startTimestamp
      ) ?>
    </p>

    <p>
      Fin :
      <?= date(
        'd/m/Y H:i',
        $result->endTimestamp
      ) ?>
    </p>

    <p>
      Prix :
      <strong>
        <?= number_format($priceInEuros, 2, ',', ' ') ?> €
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