<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row my-5">
        <div class="col-sm-6 offset-sm-3 col-10 offset-1 new-user-wrapper text-center">
            <h2><i class="far fa-envelope"></i></h2>
            <h4><?= $LNG->TXT('') ?></h4>
            <p class="my-5"><?= $LNG->TXT('', [$user_data->email]) ?></p>
            <div class="my-3">
                <a href="<?= site_url('main') ?>" class="btn btn-primary btn-200"><?= $LNG->TXT('accept') ?></a>
            </div>
        </div>
        
    </div>
</div>

<?= $this->endSection() ?>