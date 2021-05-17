<?php
    $data = $APP->get('post_data');
    $APP->printMsg();
?>
<div class="col-lg-8 col-md-12 col-xs-12">
    <div class="blog-post single-post">
        <a href="<?= $APP->getTemplateDir() ?>blog-details.html" class="post-img">
            <img class="img-fluid" src="<?= $APP->getTemplateDir() ?>assets/img/blog/blog-post1.jpg" alt="">
        </a>

        <div class="content">
            <h2>
                <a href="<?= $APP->getTemplateDir() ?>blog-details.html">Great House on The Hills</a>
            </h2>
            <ul class="post-meta">
                <li>Novemer 12, 2018</li>
                <li><a href="#">5 Comments</a></li>
            </ul>
            <p class="mb-2">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute
                irure dolor in reprehenderit in voluptate</p>
            <p>velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Sed ut
                perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque
                laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas
                sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione
                voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit
                amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut
                labore et dolore magnam aliquam quaerat voluptatem.</p>
            <ul class="share-buttons mt-5 mb-4">
                <li><a class="fb-share" href="#"><i class="lni-facebook-filled"></i> Share</a></li>
                <li><a class="twitter-share" href="#"><i class="lni-twitter-filled"></i> Tweet</a></li>
                <li><a class="gplus-share" href="#"><i class="lni-google-plus"></i> Share</a></li>
                <li><a class="linkedin-share" href="#"><i class="lni-linkedin-filled"></i> linkedin</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="author post-block">
        <h4>Meet Author</h4>
        <div class="thumb">
            <a href="#"><img src="<?= $APP->getTemplateDir() ?>assets/img/blog/author-thumb.jpg" alt=""></a>
        </div>
        <div class="body">
            <h5>Maria Marlin</h5>
            <a href="#">jennie@example.com</a>
            <p>Nullam ultricies, velit ut varius molestie, ante metus condimentum nisi, dignissim
                facilisis turpis ex in libero. Sed porta ante tortor, a pulvinar mi facilisis nec. Proin
                finibus dolor ac convallis congue.</p>
        </div>
    </div>

</div>