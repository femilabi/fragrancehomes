<div id="main-slide" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#main-slide" data-slide-to="0" class="active"></li>
        <li data-target="#main-slide" data-slide-to="1"></li>
        <li data-target="#main-slide" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img class="d-block w-100" src="<?= $APP->getTemplateDir() ?>assets/img/slider/slide1.jpg" alt="First slide">
            <div class="carousel-caption d-md-block">
                <h4 class="fadeInDown wow" data-wow-delay=".9s">Explore Amazing Houses</h4>
                <h1 class="wow fadeInDown heading" data-wow-delay=".4s">Get Started with BrittVille</h1>
                <p class="fadeInUp wow" data-wow-delay=".6s">Bootstrap HTML5 Real Estate Website Template</p>
                <a href="#" class="fadeInLeft wow btn btn-common" data-wow-delay=".6s">Download Now</a>
                <a href="#" class="fadeInRight wow btn btn-border" data-wow-delay=".6s">Learn More</a>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="<?= $APP->getTemplateDir() ?>assets/img/slider/slide2.jpg" alt="Second slide">
            <div class="carousel-caption d-md-block">
                <h4 class="fadeInDown wow" data-wow-delay=".9s">Search, Select and Go!</h4>
                <h1 class="wow bounceIn heading" data-wow-delay=".7s">Best Apartments for Sale</h1>
                <p class="fadeInUp wow" data-wow-delay=".6s">Clean and Refreshing Design for Your Next Project</p>
                <a href="#" class="fadeInUp wow btn btn-border" data-wow-delay=".8s">Learn More</a>
            </div>
        </div>
        <div class="carousel-item">
            <img class="d-block w-100" src="<?= $APP->getTemplateDir() ?>assets/img/slider/slide3.jpg" alt="Third slide">
            <div class="carousel-caption d-md-block">
                <h4 class="fadeInDown wow" data-wow-delay=".9s">Find Your Dream Home!</h4>
                <h1 class="wow fadeInUp heading" data-wow-delay=".6s">Best Apartments to Rent</h1>
                <p class="fadeInUp wow" data-wow-delay=".6s">Comes With All Essential Pages and Features</p>
                <a href="#" class="fadeInUp wow btn btn-common" data-wow-delay=".8s">View Details</a>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#main-slide" role="button" data-slide="prev">
        <span class="carousel-control" aria-hidden="true"><i class="lni-chevron-left"></i></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#main-slide" role="button" data-slide="next">
        <span class="carousel-control" aria-hidden="true"><i class="lni-chevron-right"></i></span>
        <span class="sr-only">Next</span>
    </a>
</div>