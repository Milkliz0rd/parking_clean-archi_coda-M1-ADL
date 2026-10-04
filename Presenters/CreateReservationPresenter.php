<?php

class CreateReservationPresenter implements ICreateReservationPresenter {
  private ?ReservationConfirmationViewModel $viewModel = null;

  public function present(CreateReservationResultDTO $result): void {
    $this->viewModel = new ReservationConfirmationViewModel(
      '#' . $result->parkingId,
      date('d/m/Y H:i', $result->startTimestamp),
      date('d/m/Y H:i', $result->endTimestamp),
      number_format($result->priceInCents / 100, 2, ',', ' ') . ' €'
    );
  }

  public function getViewModel(): ReservationConfirmationViewModel {
    if ($this->viewModel === null) {
      throw new LogicException(
        "Reservation has not been presented."
      );
    }

    return $this->viewModel;
  }
}