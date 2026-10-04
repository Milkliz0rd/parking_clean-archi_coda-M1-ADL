<?php

class ParkingSession {
  private ?int $id;
  private Reservation $reservation;
  private int $startTime;
  private ?int $endTime;

  public function __construct(Reservation $reservation, int $startTime, ?int $id = null) {
    $this->id = $id;
    $this->reservation = $reservation;
    $this->startTime = $startTime;
    $this->endTime = null;
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getReservation(): Reservation {
    return $this->reservation;
  }

  public function getStartTime(): int {
    return $this->startTime;
  }

  public function getEndTime(): ?int {
    return $this->endTime;
  }

  public function endSession(int $endTime): void {
    if ($this->endTime !== null) {
      throw new LogicException(
        "This parking session has already ended."
      );
    }

    if ($endTime <= $this->startTime) {
      throw new InvalidArgumentException(
        "End timestamp must be after start timestamp."
      );
    }

    $this->endTime = $endTime;
  }

  public function isActive(): bool {
    return $this->endTime === null;
  }

  public function isActiveAt(int $timestamp): bool {
    return
      $timestamp >= $this->startTime
      && (
        $this->endTime === null
        || $timestamp < $this->endTime
      );
  }
}