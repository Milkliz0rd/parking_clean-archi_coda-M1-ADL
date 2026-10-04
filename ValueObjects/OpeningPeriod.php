<?php

class OpeningPeriod {
  private DayOfWeek $startDay;
  private DayOfWeek $endDay;
  private string $startTime;
  private string $endTime;

  public function __construct(DayOfWeek $startDay, string $startTime, DayOfWeek $endDay, string $endTime) {
    if (!$this->isValidTime($startTime)) {
      throw new InvalidArgumentException(
        "Invalid start time. Expected format HH:mm."
      );
    }

    if (!$this->isValidTime($endTime)) {
      throw new InvalidArgumentException(
        "Invalid end time. Expected format HH:mm."
      );
    }

    if ($startDay === $endDay && $startTime >= $endTime) {
      throw new InvalidArgumentException(
        "For a period within the same day, end time must be after start time."
      );
    }

    $this->startDay = $startDay;
    $this->startTime = $startTime;
    $this->endDay = $endDay;
    $this->endTime = $endTime;
  }

  public function getStartDay(): DayOfWeek{
    return $this->startDay;
  }

  public function getEndDay(): DayOfWeek {
    return $this->endDay;
  }

  public function getStartTime(): string {
    return $this->startTime;
  }

  public function getEndTime(): string {
    return $this->endTime;
  }

  public function contains(int $timestamp): bool {
    $date = new DateTimeImmutable();
    $date = $date->setTimestamp($timestamp);

    $currentMinute = (((int) $date->format('N') - 1) * 1440) + ((int) $date->format('H') * 60) + (int) $date->format('i');

    $startMinute = $this->toMinuteOfWeek($this->startDay, $this->startTime);

    $endMinute = $this->toMinuteOfWeek($this->endDay, $this->endTime);

    if ($startMinute < $endMinute) {
      return $currentMinute >= $startMinute && $currentMinute < $endMinute;
    }

    return $currentMinute >= $startMinute || $currentMinute < $endMinute;
  }

  private function toMinuteOfWeek(DayOfWeek $day, string $time): int {
    [$hour, $minute] = array_map('intval', explode(':', $time));

    return (($day->value - 1) * 1440) + ($hour * 60) + $minute;
  }

  private function isValidTime(string $time): bool {
    return preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $time) === 1;
  }
}