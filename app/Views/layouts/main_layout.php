<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME . " " . APP_VERSION ?></title>
    <!-- css -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <!-- fontawesome -->
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome/all.css') ?>">
    <!-- tinyMCE -->
    <script src="https://cdn.tiny.cloud/1/<?= TINY_API_KEY ?>/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>

<body>

    <?= $this->include('layouts/main_bar') ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <!-- js -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

</body>

</html>