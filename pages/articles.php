<?php $data = $APP->get('posts_data'); ?>

<?php $APP->printMsg() ?>
<div class="col-lg-8 col-md-12 col-xs-12">

  <?php foreach ($data as $p) :
    $url = HOME_DIR . @$p['category_slug'] . '/' . @$p['slug'] . '/';
  ?>
    <div class="blog-post">
      <a href="<?= $url ?>" class="post-img">
        <img class="img-fluid" src="/<?= (@$p['image'] ?: 'images/icons/updates.png') ?>" alt="<?= @$p['title'] ?>">
      </a>

      <div class="content">
        <h2>
          <a href="<?= $url ?>"><?= @$p['title'] ?></a>
        </h2>
        <ul class="post-meta">
          <li><?= date("M d, Y", $p['created_date']) ?></li>
          <li><a href="#">By Admin</a></li>
        </ul>
        <p><?= substr(fetch_text_from_html($p['content']), 0, 100) ?>...</p>
        <a href="<?= $url ?>" class="btn btn-common read-more">Read More <i class="lni-chevron-right"></i></a>
      </div>
    </div>
  <?php endforeach; ?>

  <!-- <div class="blog-post">
    <a href="<?= $APP->getTemplateDir() ?>blog-details.html" class="post-img">
      <img class="img-fluid" src="<?= $APP->getTemplateDir() ?>assets/img/blog/blog-post2.jpg" alt="">
    </a>

    <div class="content">
      <h2>
        <a href="<?= $APP->getTemplateDir() ?>blog-details.html">Garden that has Flowers</a>
      </h2>
      <ul class="post-meta">
        <li>Novemer 12, 2018</li>
        <li><a href="#">5 Comments</a></li>
      </ul>
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, eiusmod tempor incididunt ut
        labore. Lorem ipsum dolor sit amet, consectetur adipiscing elit, eiusmod tempor
        incididunt ut labore et dolore magna aliqua. Class aptent taciti sociosqu ad litora
        torquent…</p>
      <a href="<?= $APP->getTemplateDir() ?>blog-details.html" class="btn btn-common read-more">Read More <i class="lni-chevron-right"></i></a>
    </div>
  </div> -->
</div>