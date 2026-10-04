<?php

/** @var ParkingListViewModel $parkingList */
/** @var int|null $selectedParkingId */

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title>Réserver une place</title>

    <link
      rel="stylesheet"
      href="assets/style/app.css"
    >
  </head>

  <body class='page-form'>

    <nav>
      <a href="?action=parkings">Carte des parkings</a>
      <a href="?action=create-parking">Créer un parking</a>
      <a href="?action=create-reservation">Réserver une place</a>
    </nav>

    <h1>Réserver une place</h1>

    <?php if ($selectedParkingId !== null): ?>

      <div class="selected-parking">
        Vous réservez actuellement le
        <strong>
          Parking #<?= $selectedParkingId ?>
        </strong>
      </div>

    <?php endif; ?>

    <form
      method="POST"
      action="?action=create-reservation"
    >

      <input
        type="hidden"
        name="userId"
        value="1"
      >

      <label>
        Parking

        <select
          name="parkingId"
          required
        >

          <?php foreach ($parkingList->parkings as $parking): ?>

            <option
              value="<?= $parking->id ?>"
              <?= $selectedParkingId === $parking->id ? 'selected' : '' ?>
            >
              <?= htmlspecialchars($parking->name) ?>
            </option>

          <?php endforeach; ?>

        </select>
      </label>

      <label>
        Début de la réservation

        <input
          type="datetime-local"
          name="startDateTime"
          step="900"
          required
        >
      </label>

      <label>
        Fin de la réservation
        <input
          type="datetime-local"
          name="endDateTime"
          step="900"
          required
        >
      </label>

      <button type="submit">
        Réserver
      </button>

    </form>

  </body>
</html>