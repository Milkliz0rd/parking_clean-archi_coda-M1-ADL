<?php

class Parking {
  private ?int $id;
  private Coordinates $coordinates;
  private int $capacity;
  private PricingGrid $pricingGrid;

  private array $openingPeriods = [];
  private array $reservations = [];
  private array $parkingSessions = [];

  public function __construct(Coordinates $coordinates, int $capacity, PricingGrid $pricingGrid, ?int $id = null) {
    if ($capacity <= 0) {
      throw new InvalidArgumentException(
        "A parking must have at least one parking spot."
      );
    }

    $this->id = $id;
    $this->coordinates = $coordinates;
    $this->capacity = $capacity;
    $this->pricingGrid = $pricingGrid;
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getCoordinates(): Coordinates {
    return $this->coordinates;
  }

  public function getCapacity(): int {
    return $this->capacity;
  }

  public function getPricingGrid(): PricingGrid {
    return $this->pricingGrid;
  }

  public function getOpeningPeriods(): array {
    return $this->openingPeriods;
  }

  public function getReservations(): array {
    return $this->reservations;
  }

  public function getParkingSessions(): array {
    return $this->parkingSessions;
  }

  public function addOpeningPeriod(OpeningPeriod $openingPeriod): void {
    $this->openingPeriods[] = $openingPeriod;
  }

  public function addReservation(Reservation $reservation): void {
    $this->reservations[] = $reservation;
  }

  public function addParkingSession(ParkingSession $parkingSession): void {
    $this->parkingSessions[] = $parkingSession;
  }

  private function isOpenAt(int $timestamp): bool {
    foreach ($this->openingPeriods as $openingPeriod) {
      if ($openingPeriod->contains($timestamp)) {
        return true;
      }
    }

    return false;
  }

  public function isOpenDuring(int $startTimestamp, int $endTimestamp): bool {
    if ($endTimestamp <= $startTimestamp) {
      throw new InvalidArgumentException(
        "End timestamp must be after start timestamp."
      );
    }

    for ($timestamp = $startTimestamp; $timestamp < $endTimestamp; $timestamp += 60) {
      if (!$this->isOpenAt($timestamp)) {
        return false;
      }
    }

    return $this->isOpenAt($endTimestamp - 1);
  }

  private function getOccupiedSpotsAt(int $timestamp): int {
    $occupiedSpots = 0;

    foreach ($this->reservations as $reservation) {
      if ($reservation->isActiveAt($timestamp)) {
        $occupiedSpots++;
      }
    }

    foreach ($this->parkingSessions as $parkingSession) {
      if (
        $parkingSession->isActiveAt($timestamp)
          && !$parkingSession
            ->getReservation()
            ->isActiveAt($timestamp)
      ) {
        $occupiedSpots++;
      }
    }

    return $occupiedSpots;
  }

  public function hasAvailableSpotDuring(int $startTimestamp, int $endTimestamp): bool {
    if ($endTimestamp <= $startTimestamp) {
      throw new InvalidArgumentException(
        "End timestamp must be after start timestamp."
      );
    }

    for (
        $timestamp = $startTimestamp;
        $timestamp < $endTimestamp;
        $timestamp += 60
    ) {
        if ($this->getOccupiedSpotsAt($timestamp) >= $this->capacity) {
          return false;
        }
      }

    return true;
  }
}