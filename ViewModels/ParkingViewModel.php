<?php

class ParkingViewModel {
  public function __construct(
    public readonly ?int $id,
    public readonly string $name,
    public readonly string $capacity,
    public readonly array $openingPeriods,
    public readonly array $prices
  ) {

  }
}