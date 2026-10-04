<?php
/** @var string|null $error */
?>

<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title>Créer un parking</title>

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

    <h1>Créer un parking</h1>

    <form method="POST" action="?action=create-parking">

      <input
        type="hidden"
        name="ownerId"
        value="2"
      >

      <fieldset>
        <legend>Localisation</legend>

        <label>
          Latitude
          <input
            type="number"
            step="any"
            name="latitude"
            required
          >
        </label>

        <label>
          Longitude
          <input
            type="number"
            step="any"
            name="longitude"
            required
          >
        </label>
      </fieldset>

      <label>
        Nombre de places
        <input
          type="number"
          name="capacity"
          min="1"
          required
        >
      </label>

      <fieldset>
        <legend>Tarification</legend>

        <input
          type="hidden"
          name="pricingRules[0][fromMinutes]"
          value="0"
        >

        <label>
          Prix par tranche de 15 minutes (centimes)
          <input
            type="number"
            name="pricingRules[0][pricePerQuarterHour]"
            min="0"
            required
          >
        </label>
      </fieldset>

      <fieldset>
        <legend>Horaires d'ouverture</legend>

        <label>
          Jour de début
          <select name="openingPeriods[0][startDay]" required>
            <option value="1">Lundi</option>
            <option value="2">Mardi</option>
            <option value="3">Mercredi</option>
            <option value="4">Jeudi</option>
            <option value="5">Vendredi</option>
            <option value="6">Samedi</option>
            <option value="7">Dimanche</option>
          </select>
        </label>

        <label>
          Heure de début
          <input
            type="time"
            name="openingPeriods[0][startTime]"
            required
          >
        </label>

        <label>
          Jour de fin
          <select name="openingPeriods[0][endDay]" required>
            <option value="1">Lundi</option>
            <option value="2">Mardi</option>
            <option value="3">Mercredi</option>
            <option value="4">Jeudi</option>
            <option value="5">Vendredi</option>
            <option value="6">Samedi</option>
            <option value="7">Dimanche</option>
          </select>
        </label>

        <label>
          Heure de fin
          <input
            type="time"
            name="openingPeriods[0][endTime]"
            required
          >
        </label>
      </fieldset>

      <button type="submit">
        Créer le parking
      </button>

    </form>

  </body>
</html>