<?php

class ReservationConfirmationViewModel {
  public function __construct(
    public readonly string $parking,
    public readonly string $start,
    public readonly string $end,
    public readonly string $price
  ) {

  }
}