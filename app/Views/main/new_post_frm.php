<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container mt-5">

            <div class="new-user-wrapper">

                <p class="main-title">Novo post</p>

                <?= form_open('main/new_post_submit') ?>

                <!-- post_title -->
                <div class="mb-3">
                    <label for="text_post_title" class="form-label">Título</label>
                    <input type="text" name="text_post_title" id="text_post_title" class="form-control">
                </div>

                <!-- post_message -->
                <div class="mb-3">
                    <label for="text_post_message" class="form-label">Mensagem</label>
                    <input type="hidden" name="text_post_message" id="text_post_message" class="form-control">
                    <trix-editor input="text_post_message" class="trix-content"></trix-editor>
                </div>

                <div class="row">
                    <div class="col-sm-6 col-12">
                        <a href="<?= base_url('main') ?>" class="link-app"><?= $LNG->TXT('cancel') ?></a><br>
                    </div>
                    <div class="col-sm-6 col-12 text-end">
                        <input type="submit" value="Publicar" class="btn btn-primary btn-150">
                    </div>
                </div>


                <?= form_close() ?>


            </div>


</div>

<?= $this->endSection() ?>