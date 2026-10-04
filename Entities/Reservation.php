<?php

class Reservation {
  private ?int $id;
  private ParkingUser $parkingUser;
  private Parking $parking;
  private int $reservationStart;
  private int $reservationEnd;

  public function __construct(ParkingUser $parkingUser, Parking $parking, int $reservationStart, int $reservationEnd, ?int $id = null) {
    if ($reservationEnd <= $reservationStart) {
      throw new InvalidArgumentException(
        "Reservation end must be after reservation start."
      );
    }

    $this->id = $id;
    $this->parkingUser = $parkingUser;
    $this->parking = $parking;
    $this->reservationStart = $reservationStart;
    $this->reservationEnd = $reservationEnd;
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getParkingUser(): ParkingUser {
    return $this->parkingUser;
  }

  public function getParking(): Parking {
    return $this->parking;
  }

  public function getReservationStart(): int {
    return $this->reservationStart;
  }

  public function getReservationEnd(): int {
    return $this->reservationEnd;
  }

  public function getDurationInMinutes(): int {
    return intdiv($this->reservationEnd - $this->reservationStart, 60);
  }

  public function isActiveAt(int $timestamp): bool {
      return $timestamp >= $this->reservationStart && $timestamp < $this->reservationEnd;
  }

  public function overlaps(int $start, int $end): bool {
    if ($end <= $start) {
      throw new InvalidArgumentException(
        "End timestamp must be after start timestamp."
      );
    }

    return $this->reservationStart < $end && $this->reservationEnd > $start;
  }
}