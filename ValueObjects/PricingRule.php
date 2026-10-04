<?php

class PricingRule {
  private int $fromMinutes;
  private int $pricePerQuarterHour;

  public function __construct(int $fromMinutes, int $pricePerQuarterHour) {
    if ($fromMinutes < 0) {
      throw new InvalidArgumentException(
        "Starting duration cannot be negative."
      );
    }

    if ($fromMinutes % 15 !== 0) {
      throw new InvalidArgumentException(
        "Starting duration must be a multiple of 15 minutes."
      );
    }

    if ($pricePerQuarterHour < 0) {
      throw new InvalidArgumentException(
        "Price cannot be negative."
      );
    }

    $this->fromMinutes = $fromMinutes;
    $this->pricePerQuarterHour = $pricePerQuarterHour;
  }

  public function getFromMinutes(): int {
    return $this->fromMinutes;
  }

  public function getPricePerQuarterHour(): int {
    return $this->pricePerQuarterHour;
  }
}