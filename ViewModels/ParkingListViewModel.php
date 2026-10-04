<?php

class ParkingListViewModel {
  public function __construct(
    public readonly array $parkings,
    public readonly array $markers
  ) {

  }
}