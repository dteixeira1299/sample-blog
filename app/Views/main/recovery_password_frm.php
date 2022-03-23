<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row my-5">
        <div class="col-lg-6 offset-lg-3 col-sm-8 offset-sm-2">

            <div class="login-wrapper">

                <p class="main-title"><?= $LNG->TXT('recovery_password_title') ?></p>

                <p class="my-4"><?= $LNG->TXT('recovery_password_info') ?></p>

                <?= form_open('main/recovery_password_submit') ?>


                <!-- email -->
                <div class="mb-3">
                    <label for="text_email" class="form-label"><?= $LNG->TXT('email') ?></label>
                    <input type="email" name="text_email" id="text_email" class="form-control" required minlength="10" maxlength="50" placeholder="<?= $LNG->TXT('new_user_valid_email') ?>" value="<?= old('text_email') ?>">
                </div>

                <div class="row">
                    <div class="col-sm-6 col-12">
                        <a href="<?= base_url('main/login') ?>" class="link-app"><?= $LNG->TXT('new_user_already_have_account') ?></a><br>
                        <a href="<?= base_url('main/new_user') ?>" class="link-app"><?= $LNG->TXT('sign_up') ?></a>
                    </div>
                    <div class="col-sm-6 col-12 text-end">
                        <input type="submit" value="<?= $LNG->TXT('recovery_password_recovery') ?>" class="btn btn-primary btn-150">
                    </div>
                </div>
                <br>

                <?= form_close() ?>

                <!-- validation errors -->
                <?php if (!empty($validation_errors)) : ?>
                    <div class="alert alert-danger p-2">
                        <small>
                            <?php foreach ($validation_errors as $error) : ?>
                                <i class="far fa-times-circle me-2"></i><?= $error ?><br>
                            <?php endforeach; ?>
                        </small>
                    </div>
                <?php endif; ?>


            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>