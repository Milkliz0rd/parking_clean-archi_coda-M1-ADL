<?php

class JsonParkingRepository implements IParkingRepository {
  public function __construct(private string $filePath) {
  
  }

  public function findAll(): array {
    $data = $this->readData();

    $parkings = [];

    foreach ($data as $parkingData) {
      $parkings[] = $this->toParking($parkingData);
    }

    return $parkings;
  }

  public function findById(int $id): ?Parking {
    $data = $this->readData();

    foreach ($data as $parkingData) {
      if ($parkingData['id'] === $id) {
        return $this->toParking($parkingData);
      }
    }

    return null;
  }

  public function save(Parking $parking): void {
    $data = $this->readData();

    $parkingData = $this->fromParking($parking);

    if ($parking->getId() === null) {
      $parkingData['id'] = $this->generateNextId($data);

      $data[] = $parkingData;
    } else {
      $updated = false;

      foreach ($data as $index => $existingParking) {
        if ($existingParking['id'] === $parking->getId()) {
          $data[$index] = $parkingData;
          $updated = true;
          break;
        }
      }

      if (!$updated) {
        $data[] = $parkingData;
      }
    }

    $this->writeData($data);
  }

  private function readData(): array {
    if (!file_exists($this->filePath)) {
      return [];
    }

    $json = file_get_contents($this->filePath);

    if ($json === false) {
      throw new RuntimeException(
        "Unable to read parking data file."
      );
    }

    $data = json_decode($json, true);

    if (!is_array($data)) {
      throw new RuntimeException(
        "Invalid parking JSON data."
      );
    }

    return $data;
  }

  private function writeData(array $data): void {
    $json = json_encode(
      $data,
      JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    if ($json === false) {
      throw new RuntimeException(
        "Unable to encode parking data."
      );
    }

    if (file_put_contents($this->filePath, $json) === false) {
      throw new RuntimeException(
        "Unable to write parking data file."
      );
    }
  }

  private function generateNextId(array $data): int {
    $maxId = 0;

    foreach ($data as $parkingData) {
      if ($parkingData['id'] > $maxId) {
        $maxId = $parkingData['id'];
      }
    }

    return $maxId + 1;
  }

  private function fromParking(Parking $parking): array {
    $pricingRules = [];

    foreach ($parking->getPricingGrid()->getRules() as $rule) {
      $pricingRules[] = [
        'fromMinutes' => $rule->getFromMinutes(),
        'pricePerQuarterHour' => $rule->getPricePerQuarterHour()
      ];
    }

    $openingPeriods = [];

    foreach ($parking->getOpeningPeriods() as $period) {
      $openingPeriods[] = [
        'startDay' => $period->getStartDay()->value,
        'startTime' => $period->getStartTime(),
        'endDay' => $period->getEndDay()->value,
        'endTime' => $period->getEndTime()
      ];
    }

    return [
      'id' => $parking->getId(),
      'coordinates' => [
        'latitude' => $parking->getCoordinates()->getLatitude(),
        'longitude' => $parking->getCoordinates()->getLongitude()
      ],
      'capacity' => $parking->getCapacity(),
      'pricingRules' => $pricingRules,
      'openingPeriods' => $openingPeriods
    ];
  }

  private function toParking(array $data): Parking {
    $coordinates = new Coordinates(
      $data['coordinates']['latitude'],
      $data['coordinates']['longitude']
    );

    $pricingRules = $data['pricingRules'];

    if (empty($pricingRules)) {
      throw new RuntimeException(
        "A parking must contain at least one pricing rule."
      );
    }

    $baseRuleData = $pricingRules[0];

    $pricingGrid = new PricingGrid(
      new PricingRule(
        $baseRuleData['fromMinutes'],
        $baseRuleData['pricePerQuarterHour']
      )
    );

    for ($i = 1; $i < count($pricingRules); $i++) {
      $ruleData = $pricingRules[$i];

      $pricingGrid->addRule(
        new PricingRule(
          $ruleData['fromMinutes'],
          $ruleData['pricePerQuarterHour']
        )
      );
    }

    $parking = new Parking(
      $coordinates,
      $data['capacity'],
      $pricingGrid,
      $data['id']
    );

    foreach ($data['openingPeriods'] ?? [] as $periodData) {
      $parking->addOpeningPeriod(
        new OpeningPeriod(
          DayOfWeek::from($periodData['startDay']),
          $periodData['startTime'],
          DayOfWeek::from($periodData['endDay']),
          $periodData['endTime']
        )
      );
    }

    return $parking;
  }
}