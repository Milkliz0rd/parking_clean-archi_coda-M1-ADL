<?php

class PricingGrid {
  private array $rules;

  public function __construct(PricingRule $baseRule) {
    if ($baseRule->getFromMinutes() !== 0) {
      throw new InvalidArgumentException(
        "A pricing grid must start at 0 minute."
      );
    }

    $this->rules = [$baseRule];
  }

  public function addRule(PricingRule $rule): void {
    $lastRule = $this->rules[count($this->rules) - 1];

    if ($rule->getFromMinutes()<= $lastRule->getFromMinutes()) {
      throw new InvalidArgumentException(
        "Pricing rules must be added in ascending order."
      );
    }

    $this->rules[] = $rule;
  }

  public function getRules(): array {
    return $this->rules;
  }

  public function calculatePrice(int $durationInMinutes): int {
    if ($durationInMinutes < 0 || $durationInMinutes % 15 !== 0) {
      throw new InvalidArgumentException(
        "Duration must be a positive multiple of 15 minutes."
      );
    }

    $totalPrice = 0;

    for ($minute = 0; $minute < $durationInMinutes; $minute += 15) {
      $totalPrice += $this->getPricePerQuarterHourAt($minute);
    }

    return $totalPrice;
  }

  private function getPricePerQuarterHourAt(int $minute): int {
    $price = $this->rules[0]->getPricePerQuarterHour();

    foreach ($this->rules as $rule) {
      if ($rule->getFromMinutes() > $minute) {
        break;
      }

      $price = $rule->getPricePerQuarterHour();
    }

    return $price;
  }
}