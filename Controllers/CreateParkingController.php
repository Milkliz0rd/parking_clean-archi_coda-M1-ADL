<?php

class CreateParkingController {
  public function __construct(
    private CreateParkingUseCase $useCase
  ) {
  
  }

  public function handle(array $request): void {
    $dto = new CreateParkingDTO(
      (int) $request['ownerId'],
      (float) $request['latitude'],
      (float) $request['longitude'],
      (int) $request['capacity'],
        $request['pricingRules'],
        $request['openingPeriods']
      );

    $this->useCase->execute($dto);
  }
}