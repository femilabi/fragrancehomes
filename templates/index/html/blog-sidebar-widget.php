<?php
$popular_posts = $DB->get_query_set("
    SELECT p.*, c.title AS category_title, c.slug AS category_slug 
    FROM fh_posts AS p, fh_post_categories AS c 
    WHERE (published = 1 AND p.category_id = c.id) 
    ORDER BY created_date DESC LIMIT 0, 3
");
?>
<div class="col-lg-4 col-md-12 col-xs-12">
    <div class="sidebar right">
        <div class="widget">
            <h3 class="sidebar-title">Search Blog</h3>
            <form action="/blog/" method="get">
                <div class="search-blog-input">
                    <div class="input">
                        <input class="form-control" type="text" name="q" placeholder="Type and hit enter" value="">
                        <i class="lni-search"></i>
                    </div>
                </div>
            </form>
            <div class="clearfix"></div>
        </div>
        <div class="widget">
            <h3 class="sidebar-title">Popular Posts</h3>
            <ul class="widget-tabs">
                <?php foreach ($popular_posts as $p_post) :
                    $url = "/" . $p_post["category_slug"] . "/" . $p_post["slug"];
                ?>
                    <li>
                        <div class="widget-content">
                            <div class="widget-thumb">
                                <a href="<?= $url ?>"><img src="/<?= $p_post["image"] ?>" alt="<?= $p_post["image"] ?>"></a>
                            </div>
                            <div class="widget-text">
                                <h5><a href="<?= $url ?>"><?= $p_post["title"] ?></a></h5>
                                <span><?= date("M d, Y", $p_post['created_date']) ?></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php include_once("featured-properties-widget.php"); ?>

        <?php include_once("social-media-widget.php"); ?>
    </div>
</div>