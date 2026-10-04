<?php

/** @var ParkingListItemDTO[] $parkings */

$days = [
    1 => 'Lundi',
    2 => 'Mardi',
    3 => 'Mercredi',
    4 => 'Jeudi',
    5 => 'Vendredi',
    6 => 'Samedi',
    7 => 'Dimanche'
];

$parkingData = array_map(
    fn (ParkingListItemDTO $parking) => [
        'id' => $parking->id,
        'latitude' => $parking->latitude,
        'longitude' => $parking->longitude
    ],
    $parkings
);

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

        <?php foreach ($parkings as $parking): ?>

          <article class="parking-card">
            <h2>Parking #<?= $parking->id ?></h2>
            <p>
              <strong>Capacité :</strong>
              <?= $parking->capacity ?> places
            </p>
            <h3>Horaires</h3>
            <?php if (empty($parking->openingPeriods)): ?>
            <p>Aucun horaire défini</p>
            <?php else: ?>
            <ul>
              <?php foreach ($parking->openingPeriods as $period): ?>
                <li>
                  <?= $days[$period['startDay']] ?>
                  <?= htmlspecialchars($period['startTime']) ?>
                  →
                  <?= $days[$period['endDay']] ?>
                  <?= htmlspecialchars($period['endTime']) ?>
                </li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <h3>Tarifs</h3>
            <ul>
              <?php foreach ($parking->pricingRules as $rule): ?>
              <li>
                À partir de
                <?= $rule['fromMinutes'] ?> min :
                <?= number_format($rule['pricePerQuarterHour'] / 100, 2, ',', ' ') ?>
                € / 15 min
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
      const parkings = <?= json_encode($parkingData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
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