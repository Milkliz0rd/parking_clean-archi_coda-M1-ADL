<?php

class DisplayParkingListController {
  public function __construct(
    private DisplayParkingListUseCase $useCase
  ) {
  
  }

  public function handle(): array {
    return $this->useCase->execute();
  }
}