<?php

class CreateReservationDTO {
  public function __construct(
    public readonly int $userId,
    public readonly int $parkingId,
    public readonly int $startTimestamp,
    public readonly int $endTimestamp
  ) {
    
  }
}