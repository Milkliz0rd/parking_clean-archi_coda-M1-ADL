<?php

interface IParkingRepository {
  public function findById(int $id): ?Parking;

  public function findAll(): array;

  public function save(Parking $parking): void;
}