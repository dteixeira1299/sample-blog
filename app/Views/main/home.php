<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row my-5">
        <div class="col12 text-center">

            <h1>HOME</h1>
            <?php if (!empty($post)) : ?>
                <?php foreach ($posts as $post) { ?>
                    <div class="post-wrapper text-start">
                        <a href="<?= base_url('main/post/' . $post->post_code) ?>" class="link-app"><?= $post->title; ?></a>
                    </div>
                    <br>
                <?php } ?>
            <?php else : ?>
                <p><?= $LNG->TXT('no_available_content') ?></p>
            <?php endif; ?>

        </div>
    </div>
</div>

<?= $this->endSection() ?>