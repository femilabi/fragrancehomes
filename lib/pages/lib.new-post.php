<?php 
require_login('admin'); 
$APP->setTitle("New Blog Post");
$APP->setTemplate('blank');
if(issubmit()){
	$data = @$_POST['pst'];
	if(!is_array($data)){
	}
	$post = new DBRow('posts','id', @$data['id']);
	if($post->exists()) unset($data['slug']);
	if(!$post->exists()) $post->set('created_date', time());
	$post->set_data($data);
	$post->set('published', @$data['published']? 1:0);
	
	//Social Posting
	if($post->get('published') && @$data['postsocial'] && !$post->get('social_posted')){
		$link = HOME_DIR.$post->get('slug').'/';
		fb_post($link, 'Update: "'. $post->get('title').'" Click the link to read more... '.$link);
		twitter_post($post->get('title').' '. $link.' #geo #worldgeo');
		$post->set('social_posted',1);
	}
	//assign news script
	$should_set_news = false;
	if(!$post->exists() && $post->get('category_id') == 3 && $post->get('published')){
		$should_set_news = true;
	}
	
	if($post->save()){
		$APP->setMsg('Post saved successfully', 'success');
		if($should_set_news){
			$DB->query("UPDATE ".DB_PREFIX."users SET seen_last_news = 0");
		}
	}
	$APP->assign('post_data', $post->get_data());
	
}elseif(isset($_GET['edit_post_id']) && $_GET['edit_post_id']){
	$post = new DBRow('posts','id', intval($_GET['edit_post_id']));
	if(!$post->exists()){
		$APP->setMsg('Post not found!<br/>Create new post.', 'warning');
	}
	$APP->assign('post_data', $post->get_data());
}

//Load TextEditor
$APP->addStyle(CDN_DIR.'plugins/summernote/summernote.css');
$APP->addScript(CDN_DIR.'plugins/summernote/summernote.js');

//Load file uploader
$APP->addStyle(CDN_DIR.'plugins/file_uploader/assets/css/style.css');
$APP->addScript(CDN_DIR.'plugins/file_uploader/assets/js/jquery.knob.js');
$APP->addScript(CDN_DIR.'plugins/file_uploader/assets/js/jquery.ui.widget.js');
$APP->addScript(CDN_DIR.'plugins/file_uploader/assets/js/jquery.iframe-transport.js');
$APP->addScript(CDN_DIR.'plugins/file_uploader/assets/js/jquery.fileupload.js');
$APP->addScript(CDN_DIR.'plugins/file_uploader/assets/js/script.js');
$APP->addScript('',"function upload(key){
				var uplModal = $('div#upload_modal');
				var uplTag = uplModal.find('input#file_info_tag');
				var ul = uplModal.find('#upload ul');
				ul.empty();
				uplTag.val(key);
			}
			$('.modal').hide();");
			
//Load Slug scripts
//$APP->addScript(CDN_DIR.'');
	
?>