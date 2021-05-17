<?php
$popular_posts = $DB->get_query_set("
    SELECT p.*, c.title AS category_title, c.slug AS category_slug 
    FROM fh_posts AS p, fh_post_categories AS c 
    WHERE (published = 1 AND p.category_id = c.id) 
    ORDER BY created_date DESC LIMIT 0, 3
");
?>

<section id="blog" class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title-header text-center">
                    <p>From</p>
                    <h2 class="section-title">The Blog</h2>
                </div>
            </div>
            <?php foreach ($popular_posts as $p_post) :
                $url = "/" . $p_post["category_slug"] . "/" . $p_post["slug"];
            ?>
                <div class="col-lg-4 col-md-6 col-xs-12">
                    <div class="blog-item text-center">
                        <div class="blog-image">
                            <a href="#">
                                <img class="img-fluid" width="100%" src="/<?= $p_post["image"] ?>" alt="<?= $p_post["images"] ?>">
                            </a>
                        </div>
                        <div class="date"><?= date("M d, Y", $p_post['created_date']) ?></div>
                        <div class="descr">
                            <h3 class="title">
                                <a href="<?= $url ?>"><?= $p_post["title"] ?>
                                </a>
                            </h3>
                            <?=substr(fetch_text_from_html($p_post['content']), 0, 100)?>...
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <!-- <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="blog-item text-center">
                    <div class="blog-image">
                        <a href="#">
                            <img class="img-fluid" src="<?= $APP->getTemplateDir() ?>assets/img/blog/img2.jpg" alt="">
                        </a>
                    </div>
                    <div class="date">12 April, 2018</div>
                    <div class="descr">
                        <h3 class="title">
                            <a href="<?= $APP->getTemplateDir() ?>single-blog.html">
                                Real Estate Feswtival - 2018
                            </a>
                        </h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias laudantium fugiat, eius sint.</p>
                    </div>
                    <div class="blog-footer hide-on-list">
                        <div class="float-left">
                            <p class="prop-user"><a href="#"><i class="lni-user"></i> Admin</a></p>
                        </div>
                        <div class="float-right">
                            <span><i class="lni-heart"></i> 350</span>
                            <span><i class="lni-comments"></i> 30</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-xs-12">
                <div class="blog-item text-center">
                    <div class="blog-image">
                        <a href="#">
                            <img class="img-fluid" src="<?= $APP->getTemplateDir() ?>assets/img/blog/img3.jpg" alt="">
                        </a>
                    </div>
                    <div class="date">12 April, 2018</div>
                    <div class="descr">
                        <h3 class="title">
                            <a href="<?= $APP->getTemplateDir() ?>single-blog.html">
                                Latest Architectural Design
                            </a>
                        </h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias laudantium fugiat, eius sint.</p>
                    </div>
                    <div class="blog-footer hide-on-list">
                        <div class="float-left">
                            <p class="prop-user"><a href="#"><i class="lni-user"></i> Admin</a></p>
                        </div>
                        <div class="float-right">
                            <span><i class="lni-heart"></i> 350</span>
                            <span><i class="lni-comments"></i> 30</span>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
    </div>
</section>