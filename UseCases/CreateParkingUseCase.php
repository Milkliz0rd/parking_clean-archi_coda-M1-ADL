<?php

class CreateParkingUseCase {
  public function __construct(
    private IParkingRepository $parkingRepository,
    private IUserRepository $userRepository
  ) {
  
  }

  public function execute(CreateParkingDTO $dto): void {
    $owner = $this->userRepository->findById($dto->ownerId);

    if (!$owner instanceof ParkingOwner) {
      throw new DomainException(
        "Parking owner not found."
      );
    }

    if (empty($dto->pricingRules)) {
      throw new InvalidArgumentException(
        "A parking must have at least one pricing rule."
      );
    }

    $coordinates = new Coordinates($dto->latitude, $dto->longitude);

    $baseRuleData = $dto->pricingRules[0];

    $pricingGrid = new PricingGrid(
      new PricingRule($baseRuleData['fromMinutes'], $baseRuleData['pricePerQuarterHour'])
    );

    for ($i = 1; $i < count($dto->pricingRules); $i++) {
      $ruleData = $dto->pricingRules[$i];

      $pricingGrid->addRule(
        new PricingRule($ruleData['fromMinutes'], $ruleData['pricePerQuarterHour'])
      );
    }

    $parking = new Parking($coordinates, $dto->capacity, $pricingGrid);

    foreach ($dto->openingPeriods as $periodData) {
      $parking->addOpeningPeriod(
        new OpeningPeriod(
          DayOfWeek::from($periodData['startDay']),
          $periodData['startTime'],
          DayOfWeek::from($periodData['endDay']),
          $periodData['endTime']
        )
      );
    }

    $owner->addParking($parking);

    $this->parkingRepository->save($parking);
    $this->userRepository->save($owner);
  }
}