<?= $this->extend('./layouts/main_layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row my-5">
        <div class="col12 text-center">

            <h1>HOME</h1>
            <br><br><br>

            <div class="home-wrapper">

                <div class="row">
                    <div class="col-sm-9 col-12">


                        <!-- card1 -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>
                        <br>
                        <!-- card2 -->
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Special title treatment</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>

                    </div>

                    <div class="col-sm-3 col-12">

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">About...</h5>
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                <a href="#" class="btn btn-primary">Go somewhere</a>
                            </div>
                        </div>

                    </div>


                </div>








            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>