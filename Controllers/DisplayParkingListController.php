<?php

class DisplayParkingListController {
  public function __construct(
    private DisplayParkingListUseCase $useCase
  ) {
  
  }

  public function handle(IDisplayParkingListPresenter $presenter): void {
    $this->useCase->execute($presenter);
  }
}