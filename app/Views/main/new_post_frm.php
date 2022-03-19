<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="mt-5">

    <div class="new-user-wrapper">

        <?= form_open('main/new_post_submit') ?>

        <div class="row">
            <div class="col-sm-6 col-12">
                <input type="text" class="title-new-post-frm" placeholder="Clique para editar o título do post">
            </div>
            <div class="col-sm-6 col-12 text-end">
                <a href="<?= base_url('main') ?>" class="btn btn-danger btn-150"><?= $LNG->TXT('cancel') ?></a>
                <input type="submit" name="text_post_title" id="text_post_title" value="Publicar" class="btn btn-primary btn-150">
            </div>
        </div>

        <div class="line-new-post-frm mb-3"></div>

        <!-- post_message -->
        <div class="mb-3">
            <textarea id="text_post_message" name="text_post_message" class="form-control" style="min-height: 500px;"></textarea>
        </div>


        <?= form_close() ?>


    </div>


</div>

<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'a11ychecker advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
        toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
    });
</script>
<?= $this->endSection() ?>