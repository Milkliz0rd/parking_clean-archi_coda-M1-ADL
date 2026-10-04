<?php

class ParkingListItemDTO {
  public function __construct(
    public readonly ?int $id,
    public readonly float $latitude,
    public readonly float $longitude,
    public readonly int $capacity,
    public readonly array $pricingRules,
    public readonly array $openingPeriods
  ) {
  
  }
}