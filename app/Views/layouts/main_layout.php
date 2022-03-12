<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?></title>
    <!-- css -->
    <link rel="stylesheet" href="<?= BASE_URL . 'assets/css/bootstrap.min.css' ?>">
</head>
<body>

<?= $this->include('layouts/main_bar') ?>

<?= $this->renderSection('content') ?>


<!-- js -->
<script src="<?= BASE_URL . 'assets/js/bootstrap.bundle.min.js' ?>"></script>
    
</body>
</html>