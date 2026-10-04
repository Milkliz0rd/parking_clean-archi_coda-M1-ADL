<?php

class JsonReservationRepository implements IReservationRepository {
  public function __construct(
    private string $filePath,
    private IUserRepository $userRepository,
    private IParkingRepository $parkingRepository
  ) {
  
  }

  public function findByParkingId(int $parkingId): array {
    $reservations = [];

    foreach ($this->readData() as $reservationData) {
      if ($reservationData['parkingId'] === $parkingId) {
        $reservations[] = $this->toReservation($reservationData);
      }
    }

    return $reservations;
  }

  public function save(Reservation $reservation): void {
    $data = $this->readData();

    $reservationData = $this->fromReservation($reservation);

    if ($reservation->getId() === null) {
      $reservationData['id'] = $this->generateNextId($data);

      $data[] = $reservationData;
    } else {
      $updated = false;

      foreach ($data as $index => $existingReservation) {
        if ($existingReservation['id'] === $reservation->getId()) {
          $data[$index] = $reservationData;
          $updated = true;
          break;
        }
      }

      if (!$updated) {
        $data[] = $reservationData;
      }
    }

    $this->writeData($data);
  }

  private function toReservation(array $data): Reservation {
    $user = $this->userRepository->findById($data['userId']);

    if (!$user instanceof ParkingUser) {
      throw new RuntimeException(
        "Reservation user not found."
      );
    }

    $parking = $this->parkingRepository->findById($data['parkingId']);

    if ($parking === null) {
      throw new RuntimeException(
        "Reservation parking not found."
      );
    }

    return new Reservation(
      $user,
      $parking,
      $data['startTimestamp'],
      $data['endTimestamp'],
      $data['id']
    );
  }

  private function fromReservation(Reservation $reservation): array {
    return [
      'id' => $reservation->getId(),

      'userId' =>
        $reservation
          ->getParkingUser()
          ->getId(),

      'parkingId' =>
        $reservation
          ->getParking()
          ->getId(),

      'startTimestamp' =>
        $reservation->getReservationStart(),

      'endTimestamp' =>
        $reservation->getReservationEnd()
    ];
  }

  private function generateNextId(array $data): int {
    $maxId = 0;

    foreach ($data as $reservationData) {
      if ($reservationData['id'] > $maxId) {
        $maxId = $reservationData['id'];
      }
    }

    return $maxId + 1;
  }

  private function readData(): array {
    if (!file_exists($this->filePath)) {
      return [];
    }

    $json = file_get_contents($this->filePath);

    if ($json === false) {
      throw new RuntimeException(
        "Unable to read reservation data file."
      );
    }

    $data = json_decode($json, true);

    if (!is_array($data)) {
      throw new RuntimeException(
        "Invalid reservation JSON data."
      );
    }

    return $data;
  }

  private function writeData(array $data): void {
    $json = json_encode(
      $data,
      JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    if ($json === false) {
      throw new RuntimeException(
        "Unable to encode reservation data."
      );
    }

    if (file_put_contents($this->filePath, $json) === false) {
      throw new RuntimeException(
        "Unable to write reservation data file."
      );
    }
  }
}