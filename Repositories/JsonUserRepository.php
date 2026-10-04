<?php

class JsonUserRepository implements IUserRepository {
 
  public function __construct(private string $filePath) {
  
  }

  public function findById(int $id): ?User {
    foreach ($this->readData() as $userData) {
      if ($userData['id'] === $id) {
        return $this->toUser($userData);
      }
    }

    return null;
  }

  public function save(User $user): void {
    $data = $this->readData();

    $userData = $this->fromUser($user);

    foreach ($data as $index => $existingUser) {
      if ($existingUser['id'] === $user->getId()) {
        $data[$index] = $userData;
        $this->writeData($data);
        return;
      }
    }

    $data[] = $userData;

    $this->writeData($data);
  }

  private function readData(): array {
    if (!file_exists($this->filePath)) {
      return [];
    }

    $json = file_get_contents($this->filePath);

    if ($json === false) {
      throw new RuntimeException(
        "Unable to read user data file."
      );
    }

    $data = json_decode($json, true);

    if (!is_array($data)) {
      throw new RuntimeException(
        "Invalid user JSON data."
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
        "Unable to encode user data."
      );
    }

    if (file_put_contents($this->filePath, $json) === false) {
      throw new RuntimeException(
        "Unable to write user data file."
      );
    }
  }

  private function toUser(array $data): User {
    if ($data['type'] === 'owner') {
      return new ParkingOwner(
        $data['email'],
        $data['password'],
        $data['lastName'],
        $data['firstName'],
        $data['id']
      );
    }

    return new ParkingUser(
      $data['email'],
      $data['password'],
      $data['lastName'],
      $data['firstName'],
      $data['id']
    );
  }

  private function fromUser(User $user): array {
    return [
      'id' => $user->getId(),
      'type' => $user instanceof ParkingOwner ? 'owner' : 'user',
        'email' => $user->getEmail(),
        'password' => $user->getPassword(),
        'lastName' => $user->getLastName(),
        'firstName' => $user->getFirstName()
    ];
  }
}