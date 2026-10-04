<?php

date_default_timezone_set('Europe/Paris');

spl_autoload_register(function (string $class): void {
  $directories = [
    __DIR__ . '/Entities/',
    __DIR__ . '/ValueObjects/',
    __DIR__ . '/Enums/',
    __DIR__ . '/DTO/',
    __DIR__ . '/Repositories/',
    __DIR__ . '/UseCases/',
    __DIR__ . '/Controllers/',
    __DIR__ . '/Presenters/',
    __DIR__ . '/ViewModels/',
    __DIR__ . '/Infrastructure/Repositories/',
  ];

  foreach ($directories as $directory) {
    $file = $directory . $class . '.php';

    if (file_exists($file)) {
      require_once $file;
      return;
    }
  }
});

$parkingRepository = new JsonParkingRepository(__DIR__ . '/data/parkings.json');

$userRepository = new JsonUserRepository(__DIR__ . '/data/users.json');

$reservationRepository = new JsonReservationRepository(
  __DIR__ . '/data/reservations.json',
  $userRepository,
  $parkingRepository
);

$displayParkingListUseCase = new DisplayParkingListUseCase($parkingRepository);

$createParkingUseCase = new CreateParkingUseCase($parkingRepository, $userRepository);

$createReservationUseCase =
  new CreateReservationUseCase(
    $parkingRepository,
    $userRepository,
    $reservationRepository
  );

$displayParkingListController = new DisplayParkingListController($displayParkingListUseCase);

$createParkingController = new CreateParkingController($createParkingUseCase);

$createReservationController = new CreateReservationController($createReservationUseCase);

$action = $_GET['action'] ?? 'parkings';

try {
  switch ($action) {
    case 'parkings':
      $parkingListPresenter = new DisplayParkingListPresenter();
      $displayParkingListController->handle($parkingListPresenter);
      $parkingList = $parkingListPresenter->getViewModel();

      require __DIR__ . '/Views/parking-list.php';
      break;

    case 'create-parking':
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $createParkingController->handle($_POST);

        header('Location: ?action=parkings');
        exit;
      }

      require __DIR__ . '/Views/create-parking.php';
      break;

    case 'create-reservation':
      $parkingListPresenter = new DisplayParkingListPresenter();
      $displayParkingListController->handle($parkingListPresenter);
      $parkingList = $parkingListPresenter->getViewModel();

      $selectedParkingId = isset($_GET['parkingId']) ? (int) $_GET['parkingId'] : null;

      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $createReservationPresenter = new CreateReservationPresenter();
        $createReservationController->handle($_POST, $createReservationPresenter);
        $confirmation = $createReservationPresenter->getViewModel();

        require __DIR__ . '/Views/reservation-success.php';

        break;
      }

      require __DIR__ . '/Views/create-reservation.php';

      break;
      
    default:
      http_response_code(404);
      echo 'Page not found.';
  }
} catch (Throwable $exception) {
  http_response_code(400);

  $error = $exception->getMessage();

  require __DIR__ . '/Views/error.php';
}