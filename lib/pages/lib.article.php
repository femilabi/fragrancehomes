<?php

$APP->setTemplate('blog');
if (isset($_GET["slug"]) && $_GET["slug"]) {
	$post = new DBRow('posts', 'id');
	$post->search('slug', $_GET["slug"]);
	$category = new DBRow('post_categories', 'id', $post->get('category_id'));
	if ($post->exists() && @$_GET['category'] == $category->get('slug') && ($post->get('published') || $USER->get('role') == 'admin')) {
		$APP->assign('post_data', $post->get_data());
		//$APP->assign('enable_fb_comments', 1);
		$category = new DBRow('post_categories', 'id', $post->get('category_id'));
		if ($category->exists()) {
		}

		$APP->setTemplate('blog');
		//SEO
		$APP->setTitle($post->get('title'));
		$APP->setMeta('description', ($post->get('description') ? $post->get('description') : trim(substr(str_pad(@fetch_text_from_html($post->get('content')), 255), 0, 255))) . ' ' . $APP->getMeta('description'));
		$APP->setMeta('keywords', $post->get('description') . ', geo blogs, geographical articles,' . $APP->getMeta('keywords'));
		$APP->assign('enable_fb_comments', 1);
		$image = $post->get('image') && file_exists($post->get('image')) ? $post->get('image') : 'images/post_thumbs/default.png';
		//Update your html tag to include the itemscope and itemtype attributes.
		$APP->setHTMLAttribute('itemscope', '');
		$APP->setHTMLAttribute('itemtype', 'http://schema.org/Article');
		$metas = array(
			'title' => $APP->getFullTitle(),
			'type' => 'article',
			'card' => 'summary_large_image',
			'site_name' => 'Mutual Helpers',
			'description' => $APP->getMeta('description'),
			'image' => HOME_DIR . $image,
			'creator' => '@mutualhelpers',
			'section' => $category->get('title'),
			'tag' => $post->get('keywords'),
			'published_time' => date(DATE_ATOM, $post->get('created_date')),
			'modified_time' => date(DATE_ATOM, $post->get('modified_date')),
			'url' => get_current_url(0),
			'fb_admin' => '1145580792119558'
		);
		$APP->assign('social_meta_data', $metas);
	} else {
		$APP->setPage('404');
		$APP->setMsg('Post not found!');
	}
}
