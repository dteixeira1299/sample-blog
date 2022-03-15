<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row my-5">

        <div class="col-sm-6 offset-sm-3 col-10 offset-1 new-user-wrapper text-center">

            <h1><i class="far fa-check-circle"></i></h1>

            <p class="my-4"><?= $LNG->TXT('new_account_email_verified') ?></p>

            <a href="<?= site_url('main/login') ?>" class="btn btn-primary btn-200"><?= $LNG->TXT('login') ?></a>

        </div>

    </div>
</div>

<?= $this->endSection() ?>