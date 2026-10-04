<?php

class DisplayParkingListPresenter implements IDisplayParkingListPresenter {
  private const DAYS = [
    1 => 'Lundi',
    2 => 'Mardi',
    3 => 'Mercredi',
    4 => 'Jeudi',
    5 => 'Vendredi',
    6 => 'Samedi',
    7 => 'Dimanche'
  ];

  private ?ParkingListViewModel $viewModel = null;

  public function present(array $parkings): void {
    $parkingViewModels = [];

    $markers = [];

    foreach ($parkings as $parking) {
      $openingPeriods = [];

      foreach ($parking->openingPeriods as $period) {
        $openingPeriods[] =
          self::DAYS[$period['startDay']] . ' ' . $period['startTime'] . ' → ' .
          self::DAYS[$period['endDay']] . ' ' . $period['endTime'];
      }

      $prices = [];

      foreach ($parking->pricingRules as $rule) {
        $prices[] =
          'À partir de ' . $rule['fromMinutes'] . ' min : ' .
          number_format($rule['pricePerQuarterHour'] / 100, 2, ',', ' ') . ' € / 15 min';
      }

      $parkingViewModels[] = new ParkingViewModel(
        $parking->id,
        'Parking #' . $parking->id,
        $parking->capacity . ' places',
        $openingPeriods,
        $prices
      );

      $markers[] = [
        'id' => $parking->id,
        'latitude' => $parking->latitude,
        'longitude' => $parking->longitude
      ];
    }

    $this->viewModel = new ParkingListViewModel($parkingViewModels, $markers);
  }

  public function getViewModel(): ParkingListViewModel {
    if ($this->viewModel === null) {
      throw new LogicException(
        "Parking list has not been presented."
      );
    }

    return $this->viewModel;
  }
}