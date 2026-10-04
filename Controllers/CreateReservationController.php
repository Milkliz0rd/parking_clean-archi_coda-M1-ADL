<?php

class CreateReservationController {
  public function __construct(
    private CreateReservationUseCase $useCase
  ) {
  
  }

  public function handle(array $request): CreateReservationResultDTO {

    $startTimestamp = strtotime($request['startDateTime']);

    $endTimestamp = strtotime($request['endDateTime']);

    if ($startTimestamp === false || $endTimestamp === false) {
      throw new InvalidArgumentException(
        "Invalid reservation date."
      );
    }

    $dto = new CreateReservationDTO(
      (int) $request['userId'],
      (int) $request['parkingId'],
      $startTimestamp,
      $endTimestamp
    );

    return $this->useCase->execute($dto);
  }
}