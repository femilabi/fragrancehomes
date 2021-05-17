<?php $data = $APP->get('posts_data'); ?>
<h2 style="width:100%">Manage Posts <a class="btn btn-default pull-right" href="new-post/">New Post</a></h2>
 <hr />
<form method="GET" action="" class="form-inline" data-ajax-type="json">
 <h4>Filter posts</h4>
  <div class="form-group">
    <label for="filter_category_id">By Category:</label>
    <select class="form-control" id="filter_category_id" name="filter[category_id]">
    	<option value="">All</option>
        <?php echo Table::load_combo_from_table('post_categories','{title}', $APP->get('filter_category_id'),'id'); ?>
    </select>
  </div>
  <div class="form-group">
    <label for="type">By Status:</label>
    <select class="form-control" id="type" name="filter[published]">
    	<option value="">All</option>
    	<option value="0"<?php echo($APP->get('filter_published') == '0'? " selected": ''); ?>>Draft</option>
    	<option value="1"<?php echo($APP->get('filter_published') == '1'? " selected": ''); ?>>Published</option>
    </select>
  </div>
  <button type="submit" name="action" value="filter" class="btn btn-default">Filter</button>
  
    <div class="input-group pull-right">
      <input type="text" class="form-control" name="q" value="<?=$APP->get('search_q'); ?>" placeholder="Search for...">
      <span class="input-group-btn">
        <button class="btn btn-default" name="action" value="search" type="submit"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
      </span>
    </div>
</form>
<hr/>
<?php if($APP->get('search_q')): ?>
    <div id="filterInfo" class="alert alert-info">
        Displaying search results for <strong>&quot;<?=$APP->get('search_q'); ?>&quot;</strong><?php echo $APP->get('filter_role')? ', Access Level: <strong>'.$APP->get('filter_role').'</strong>' : ''; ?><?php echo $APP->get('filter_teaching') == '' ? '' : ', Type: '.($APP->get('filter_teaching')? '<strong>Teaching</strong>' : '<strong>Non-Teaching</strong>'); ?>
    	<br/><br/><a href="manage-posts/" class="btn btn-info btn-sm">Clear Search and Filters</a>
    </div>
<?php endif; ?>
<?php $APP->printMsg(); ?>
<?php
  make_pager(); 
  $table = new Table();
  $table->set_columns(array('title' => 'Title',
  							'category_title' => 'Category',
  							'status' => 'Test Status',
  							'created_date' => 'Added on'
						));
  $table->set_data($data);
  $table->set_links(array('title' => array('url' => HOME_DIR.'new-post/?edit_post_id={id}')));
  $table->set_date_columns(array('created_date' => 'default'));
  $table->set_search(array('q' => $APP->get('search_q'),
  							'fields' => array('title', 'content')
							));
  $table->set_sort(array('title', 'created_date','creator_id', 'category_title'));
  $table->set_options(array(array('text' => 'Edit',
  							'type' => 'info',
							'icon' => 'edit',
							'url' => HOME_DIR.'new-post/?edit_post_id={id}'
							),
							array('text' => 'View',
  							'type' => 'default',
  							'target' => '_blank',
							'icon' => 'eye-open',
							'url' => HOME_DIR.'blog/{slug}'
							),
							array('text' => 'Publish',
  							'type' => 'default',
							'icon' => 'ok',
							'url' => HOME_DIR.'manage-posts/?post_id={id}&action=publish&redirect='.urlencode(get_current_url()),
							'depends_key' => 'published',
							'depends_value' => 0
							),
							array('text' => 'Draft',
  							'type' => 'default',
							'icon' => 'remove',
							'url' => HOME_DIR.'manage-posts/?post_id={id}&action=draft&redirect='.urlencode(get_current_url()),
							'depends_key' => 'published',
							'depends_value' => 1
							),
					)
	);
  $table->set_ajax_src('posts_data');
  echo $table->print_output();
  make_pager(); 
?>