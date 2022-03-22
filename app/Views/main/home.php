<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row my-5">
        <div class="col12 text-center">

            <?php if ($posts) : ?>
                <?php foreach ($posts as $post) { ?>
                    <div class="post-wrapper text-start">
                        <a href="<?= base_url('main/posts/' . $post->post_code) ?>" class="link-app"><?= $post->title; ?></a>

                    </div>
                    <br>
                <?php } ?>
            <?php else : ?>
                <h4><?= $LNG->TXT('no_available_content') ?></h4>
            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>