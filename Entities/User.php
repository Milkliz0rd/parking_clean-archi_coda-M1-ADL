<?php

abstract class User {
  protected ?int $id;
  protected string $email;
  protected string $password;
  protected string $lastName;
  protected string $firstName;

  public function __construct(string $email, string $password, string $lastName, string $firstName, ?int $id = null) {
    $this->id = $id;
    $this->email = $email;
    $this->password = $password;
    $this->lastName = $lastName;
    $this->firstName = $firstName;
  }

  public function getId(): ?int {
    return $this->id;
  }

  public function getEmail(): string {
    return $this->email;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function getLastName(): string {
    return $this->lastName;
  }

  public function getFirstName(): string {
    return $this->firstName;
  }
}