<div class="container-fluid main-bar fixed-top">
    <div class="row">

        <div class="col-6 p-2">
            <a href="<?= site_url('main') ?>" class="link-logo">
                <h3 class="align-self-center m-1"><?= APP_NAME ?></h3>
            </a>
        </div>

        <div class="col-6 p-2 text-end align-self-center">

            <a href="<?= site_url('main') ?>" class="link-app"><?= $LNG->TXT('home') ?></a>

            <span class="mx-2 opacity-50">|</span>

            <?php if (session()->has('user')) : ?>
                <?php if (session('user')['profile'] > 1) : ?>
                    <a href="<?= site_url('main/new_post') ?>" class="link-app"><?= $LNG->TXT('new_post') ?></a>
                    <span class="mx-2 opacity-50">|</span>
                <?php endif; ?>
                <i class="far fa-user me-2"></i>
                <a href="#" class="link-app"><?= session('user')['username'] ?></a>
                <span class="mx-2 opacity-50">|</span>
                <a href="<?= site_url('main/logout') ?>" class="link-app"><?= $LNG->TXT('logout') ?></a>
            <?php else : ?>
                <a href="<?= site_url('main/login') ?>" class="link-app"><?= $LNG->TXT('login') ?></a>
                <span class="mx-2 opacity-50">|</span>
                <a href="<?= site_url('main/new_user') ?>" class="link-app"><?= $LNG->TXT('sign_up') ?></a>

            <?php endif; ?>

            <a href="<?= site_url('main/change_language/pt_PT') ?>"><img src="<?= base_url('assets/images/icons/icon_lang_pt.png') ?>" alt="Português" class="m-1" width="30"></a>
            <a href="<?= site_url('main/change_language/en_US') ?>"><img src="<?= base_url('assets/images/icons/icon_lang_en.png') ?>" alt="English" class="m-1" width="30"></a>

        </div>

    </div>
</div>
<br>
<br>