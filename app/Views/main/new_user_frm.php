<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row my-5">
        <div class="col-lg-6 offset-lg-3 col-sm-8 offset-sm-2">

            <div class="new-user-wrapper">

                <p class="main-title"><?= $LNG->TXT('new_user_title') ?></p>

                <?= form_open('main/new_user_submit', ['novalidate' => true]) ?>

                <!-- username -->
                <div class="mb-3">
                    <label for="text_username" class="form-label"><?= $LNG->TXT('name') ?></label>
                    <input type="text" name="text_username" id="text_username" class="form-control" require minlength="10" maxlength="20" placeholder="<?= $LNG->TXT('name') ?>">
                </div>

                <!-- email -->
                <div class="mb-3">
                    <label for="text_email" class="form-label"><?= $LNG->TXT('email') ?></label>
                    <input type="email" name="text_email" id="text_email" class="form-control" require minlength="10" maxlength="50" placeholder="<?= $LNG->TXT('new_user_valid_email') ?>">
                </div>

                <!-- password -->
                <div class="mb-3">
                    <label for="text_password" class="form-label"><?= $LNG->TXT('password') ?></label>
                    <input type="text" name="text_password" id="text_password" class="form-control" require minlength="6" maxlength="16" placeholder="<?= $LNG->TXT('password') ?>">
                </div>

                <!-- repeat password -->
                <div class="mb-3">
                    <label for="text_repeat_password" class="form-label"><?= $LNG->TXT('new_user_repeat_password') ?></label>
                    <input type="text" name="text_repeat_password" id="text_repeat_password" class="form-control" require minlength="6" maxlength="16" placeholder="<?= $LNG->TXT('new_user_repeat_password') ?>">
                </div>

                <div class="row">
                    <div class="col-sm-6 col-12">
                        <a href="#" class="link-app"><?= $LNG->TXT('new_user_already_have_account') ?></a><br>
                        <a href="#" class="link-app"><?= $LNG->TXT('new_user_recover_password') ?></a>
                    </div>
                    <div class="col-sm-6 col-12 text-end">
                        <input type="submit" value="<?= $LNG->TXT('new_user_create_account') ?>" class="btn btn-primary">
                    </div>
                </div>
                
                <br>

                <div class="mb-3 text-center">
                    <?= $LNG->TXT('new_user_disclaimer') ?>
                </div>

                <?= form_close() ?>

            </div>

            <div>
                <span class="link-app" onclick="preencher()">PREENCHER</span>
            </div>

        </div>
    </div>
</div>

<script>
    function preencher() 
    {
        document.querySelector("#text_username").value="Diogo Teixeira";
        document.querySelector("#text_email").value="diogo.teixeira@gmail.com";
        document.querySelector("#text_password").value="Aa123456";
        document.querySelector("#text_repeat_password").value="Aa123456";
    }
</script>

<?= $this->endSection() ?>