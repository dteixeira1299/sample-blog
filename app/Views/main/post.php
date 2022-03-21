<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row my-5">
        <div class="col12">

            <h2><?= $title ?></h2>
            <hr>
            <small><?= $LNG->TXT('by') ?> <strong><?= $id_user ?></strong> | <?= $LNG->TXT('date') ?> <strong><?= date_format (new DateTime($created_at), 'd-m-Y'); ?></strong></small>

            <div class="post-wrapper mt-3"><?= $content ?></div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>