<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row my-5">
        <div class="col12 text-center">
            <h3>Últimos Posts:</h3>

            <div class="card p-3 my-1" style="width: 18rem;">
                <img src="https://image.shutterstock.com/image-vector/new-blog-post-speech-bubble-260nw-1917351452.jpg" class="card-img-top" alt="...">
                <div class="card-body">
                    <h5 class="card-title mt-4">Card title</h5>
                    <p class="card-text mt-2">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    <a href="#" class="btn btn-primary mt-2">Read more....</a>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?= $this->endSection() ?>