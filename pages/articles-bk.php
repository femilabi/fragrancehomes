<!-- <?php $data = $APP->get('posts_data'); ?><?php $APP->printMsg() ?>
<?php //print_thumbs($APP->get('thumb_data')); 
?>
<hr/>
<div class="row">
<?php
if (is_array($data)) {
  foreach ($data as $p) :
    $url = HOME_DIR . @$p['category_slug'] . '/' . @$p['slug'] . '/';
?>
	<div class="col-md-4">
      <div class="thumbnail">
        <a href="<?= $APP->getTemplateDir() ?><?= $url ?>" class="blog-thumb"><?php if (@$p['image']) : ?><img class="img-responsive" src="<?= $APP->getTemplateDir() ?><?= @$p['image'] ? $p['image'] : 'images/icons/updates.png' ?>" /><?php endif; ?></a>
        <div class="caption">
          <h3><a href="<?= $APP->getTemplateDir() ?><?= $url ?>" class=" project-thumb"><?= @$p['title'] ?></a></h3>
          <a href="<?= $APP->getTemplateDir() ?><?= $url ?>" class="pull-right btn btn-xs">More &gt;&gt;</a>
          <p><?= @$p['description'] ? $p['description'] : '...' ?></p>
        </div>
      </div>
    </div>
    <hr class="visible-sm"/>
<?php
  endforeach;
  make_pager();
}
?>

</div> -->