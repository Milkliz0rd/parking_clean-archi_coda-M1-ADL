<?php

class Coordinates {
  private float $longitude;
  private float $latitude;

  public function __construct(float $latitude, float $longitude) {
    
    if($latitude < -90 || $latitude > 90) {
      throw new InvalidArgumentException(
        "Invalid latitude, must be between -90 and 90"
      );
    }

    if($longitude < -180 || $longitude > 180) {
      throw new InvalidArgumentException(
        "Invalid longitude, must be between -180 and 180"
      );
    }

    $this->latitude = $latitude;
    $this->longitude = $longitude;
    
  }

  public function getLongitude(): float {
    return $this->longitude;
  }

  public function getLatitude(): float {
    return $this->latitude;
  }
}