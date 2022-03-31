<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="row my-5">
        <div class="col-lg-6 offset-lg-3 col-sm-8 offset-sm-2">

            <div class="new-user-wrapper">

                <p class="main-title"><?= $LNG->TXT('redifine_password_title') ?></p>

                <?= form_open('main/redifine_password_submit') ?>

                <input type="hidden" name="user_code" value="<?= $user_code ?>">

                <!-- password -->
                <div class="mb-3">
                    <label for="text_password" class="form-label"><?= $LNG->TXT('password') ?></label>
                    <input type="password" name="text_password" id="text_password" class="form-control" required minlength="6" maxlength="16" placeholder="<?= $LNG->TXT('password') ?>">
                </div>

                <!-- repeat password -->
                <div class="mb-3">
                    <label for="text_repeat_password" class="form-label"><?= $LNG->TXT('new_user_repeat_password') ?></label>
                    <input type="password" name="text_repeat_password" id="text_repeat_password" class="form-control" required minlength="6" maxlength="16" placeholder="<?= $LNG->TXT('new_user_repeat_password') ?>">
                </div>

                <div class="mb-3 text-end">
                    <input type="submit" value="<?= $LNG->TXT('update') ?>" class="btn btn-primary btn-150">
                </div>

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