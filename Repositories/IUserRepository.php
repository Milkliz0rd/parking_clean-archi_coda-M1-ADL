<?php

interface IUserRepository {
  public function findById(int $id): ?User;

  public function save(User $user): void;

}