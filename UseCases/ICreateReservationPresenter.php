<?php

interface ICreateReservationPresenter {
  public function present(CreateReservationResultDTO $result): void;
}