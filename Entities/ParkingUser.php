<?php

class ParkingUser extends User {
  private array $reservations = [];
  private array $parkingSessions = [];

  public function addReservation(Reservation $reservation): void {
    $this->reservations[] = $reservation;
  }

  public function addParkingSession(ParkingSession $parkingSession): void {
    $this->parkingSessions[] = $parkingSession;
  }

  public function getReservations(): array {
    return $this->reservations;
  }

  public function getParkingSessions(): array {
    return $this->parkingSessions;
  }
}