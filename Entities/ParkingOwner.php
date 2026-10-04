<?php

class ParkingOwner extends User {
  private array $parkings = [];

  public function addParking(Parking $parking): void {
    $this->parkings[] = $parking;
  }

  public function getParkings(): array {
    return $this->parkings;
  }
}