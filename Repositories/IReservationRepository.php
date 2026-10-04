<?php

interface IReservationRepository {
  public function findByParkingId(int $parkingId): array;

  public function save(Reservation $reservation): void;
}