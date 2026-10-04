<?php

class DisplayParkingListUseCase {
  public function __construct(private IParkingRepository $parkingRepository) {
  
  
  }

  public function execute(IDisplayParkingListPresenter $presenter): void {
    $parkings = $this->parkingRepository->findAll();

    $parkingList = [];

    foreach ($parkings as $parking) {
      $coordinates = $parking->getCoordinates();

      $pricingRules = [];

      foreach ($parking->getPricingGrid()->getRules() as $rule) {
        $pricingRules[] = [
          'fromMinutes' => $rule->getFromMinutes(),
          'pricePerQuarterHour' =>
            $rule->getPricePerQuarterHour()
        ];
      }

      $openingPeriods = [];

      foreach ($parking->getOpeningPeriods() as $period) {
        $openingPeriods[] = [
          'startDay' => $period->getStartDay()->value,
          'startTime' => $period->getStartTime(),
          'endDay' => $period->getEndDay()->value,
          'endTime' => $period->getEndTime()
        ];
      }

      $parkingList[] = new ParkingListItemDTO(
        $parking->getId(),
        $coordinates->getLatitude(),
        $coordinates->getLongitude(),
        $parking->getCapacity(),
        $pricingRules,
        $openingPeriods
      );
    }

    $presenter->present($parkingList);
  }
}