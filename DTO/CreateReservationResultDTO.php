<?php

class CreateReservationResultDTO {
  public function __construct(
    public readonly int $parkingId,
    public readonly int $startTimestamp,
    public readonly int $endTimestamp,
    public readonly int $priceInCents
  ) {
    
  }
}