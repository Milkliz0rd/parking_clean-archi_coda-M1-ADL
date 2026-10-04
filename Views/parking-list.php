<?php

/** @var ParkingListViewModel $parkingList */

?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title>Parkings disponibles</title>

    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    >
    <link
      rel="stylesheet"
      href="./assets/style/app.css"
    >
  </head>

  <body class='page-map'>

    <nav>
      <a href="?action=parkings">Carte des parkings</a>
      <a href="?action=create-parking">Créer un parking</a>
      <a href="?action=create-reservation">Réserver une place</a>
    </nav>

    <div class="map-content">

      <div id="map"></div>

      <aside class="parking-list">

        <h1>Parkings</h1>

        <?php foreach ($parkingList->parkings as $parking): ?>

          <article class="parking-card">
            <h2><?= htmlspecialchars($parking->name) ?></h2>
            <p>
              <strong>Capacité :</strong>
              <?= htmlspecialchars($parking->capacity) ?>
            </p>
            <h3>Horaires</h3>
            <?php if (empty($parking->openingPeriods)): ?>
            <p>Aucun horaire défini</p>
            <?php else: ?>
            <ul>
              <?php foreach ($parking->openingPeriods as $period): ?>
                <li>
                  <?= htmlspecialchars($period) ?>
                </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <h3>Tarifs</h3>
            <ul>
              <?php foreach ($parking->prices as $price): ?>
              <li>
                <?= htmlspecialchars($price) ?>
              </li>
              <?php endforeach; ?>
            </ul>
            <a class="reserve-button" href="?action=create-reservation&parkingId=<?= $parking->id ?>">
              Réserver
            </a>

          </article>

        <?php endforeach; ?>
      </aside>
    </div>

    <script
      src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js">
    </script>

    <script>
      const parkings = <?= json_encode($parkingList->markers, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
      const map = L.map('map').setView([46.603354, 1.888334], 6);

      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',
        {
          attribution: '&copy; OpenStreetMap contributors'
        }
      ).addTo(map);

      const bounds = [];

      parkings.forEach(parking => {
        const coordinates = [
          parking.latitude,
          parking.longitude
        ];

        L.marker(coordinates)
          .addTo(map)
          .bindPopup(
            `Parking #${parking.id}`
          );

        bounds.push(coordinates);
      });

      if (bounds.length === 1) {
        map.setView(bounds[0], 15);
      } else if (bounds.length > 1) {
        map.fitBounds(bounds, {
          padding: [40, 40]
        });
      }
    </script>

  </body>
</html>