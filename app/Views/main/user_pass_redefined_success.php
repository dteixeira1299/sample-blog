<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row my-5">
        <div class="col-sm-6 offset-sm-3 col-10 offset-1 new-user-wrapper text-center">
            <h4 class="mt-3"><?= $LNG->TXT('redifine_password_info') ?></h4>
            <div class="mt-5">
                <a href="<?= site_url('main/login') ?>" class="btn btn-primary btn-200"><?= $LNG->TXT('back') ?></a>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>