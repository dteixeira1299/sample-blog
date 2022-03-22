<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container post-wrapper mt-5">
    <div class="row">

        <div class="col-sm-6 col-12 my-1">
            <h2><?= $title ?></h2>
        </div>
        <div class="col-sm-6 col-12 text-end">
            <a href="<?= base_url('main') ?>" class="btn btn-primary btn-150">[EDIT]</a>
        </div>

        <div class="line-post mb-3"></div>

        <small>
            <?= $LNG->TXT('by') ?> <strong><?= $id_user ?></strong> |
            <?= $LNG->TXT('date') ?> <strong><?= date_format(new DateTime($created_at), 'd-m-Y'); ?></strong>
            <?php if ($created_at != $updated_at) : ?>
                | <?= $LNG->TXT('updated') ?> <strong><?= date_format(new DateTime($updated_at), 'd-m-Y'); ?></strong>
            <?php endif; ?>
        </small>

        <div class="mt-5" style="overflow-wrap: break-word;"><?= $content ?></div>

    </div>
</div>

<?= $this->endSection() ?>