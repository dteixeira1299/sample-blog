<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="mt-5 container new-post-wrapper">

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

    <?= form_open('main/edit_post_submit') ?>

    <input type="hidden" name="post_code" value="<?= $post_code ?>">

    <div class="row">
        <div class="col-sm-6 col-12">
            <input type="text" class="title-new-post-frm my-1 ms-2" name="text_post_title" id="text_post_title" placeholder="<?= $LNG->TXT('title') ?>" value="<?= $title ?>" required minlength="10" maxlength="50">
        </div>
        <div class="col-sm-6 col-12 text-end">
            <a href="<?= base_url('main/posts/' . $post_code) ?>" class="btn btn-danger btn-150"><?= $LNG->TXT('cancel') ?></a>
            <input type="submit" value="<?= $LNG->TXT('publish') ?>" class="btn btn-primary btn-150">
        </div>
    </div>

    <div class="line-new-post-frm mb-3"></div>

    <!-- post_message -->
    <div class="mb-3">
        <textarea id="text_post_message" name="text_post_message" class="form-control" style="height: 100vh;"></textarea>
    </div>


    <?= form_close() ?>


</div>

<script>
    tinymce.init({
        selector: 'textarea',
        plugins: 'a11ychecker advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
        toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table',
        toolbar_mode: 'floating',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        language: <?= json_encode($LNG->selected_language); ?>,
        skin: "fabric-dark",
        skin: "oxide-dark",
        content_css: "dark",
        setup: function(editor) {
            editor.on('init', function(e) {
                editor.setContent(<?= json_encode($content) ?>);
            });
        }
    });
</script>
<?= $this->endSection() ?>