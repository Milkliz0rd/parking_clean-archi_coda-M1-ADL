<?php

class CreateReservationUseCase {
  public function __construct(
    private IParkingRepository $parkingRepository,
    private IUserRepository $userRepository,
    private IReservationRepository $reservationRepository
  ) {
  
  }

  public function execute(CreateReservationDTO $dto, ICreateReservationPresenter $presenter): void {

    $user = $this->userRepository->findById($dto->userId);

    if (!$user instanceof ParkingUser) {
      throw new DomainException(
        "Parking user not found."
      );
    }

    $parking = $this->parkingRepository->findById(
      $dto->parkingId
    );

    if ($parking === null) {
      throw new DomainException(
        "Parking not found."
      );
    }

    $reservation = new Reservation(
      $user,
      $parking,
      $dto->startTimestamp,
      $dto->endTimestamp
    );

    $existingReservations = $this->reservationRepository->findByParkingId($dto->parkingId);

    foreach ($existingReservations as $existingReservation) {
      $parking->addReservation($existingReservation);
    }

    if (!$parking->isOpenDuring($dto->startTimestamp, $dto->endTimestamp)) {
      throw new DomainException(
        "Parking is closed during this period."
      );
    }

    if (!$parking->hasAvailableSpotDuring($dto->startTimestamp, $dto->endTimestamp)) {
      throw new DomainException(
        "Parking is full during this period."
      );
    }

    $priceInCents = $parking
      ->getPricingGrid()
      ->calculatePrice(
        $reservation->getDurationInMinutes()
      );

    $parking->addReservation($reservation);
    $user->addReservation($reservation);

    $this->reservationRepository->save($reservation);

    $presenter->present(new CreateReservationResultDTO(
      $dto->parkingId,
      $dto->startTimestamp,
      $dto->endTimestamp,
      $priceInCents
    ));
  }
}