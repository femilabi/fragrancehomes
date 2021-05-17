<?php $data = $APP->get('post_data'); ?>
<!-- <div class="main-container section-padding">
  <div class="container">
    <div class="row"> -->
<div class="col-lg-12 col-md-12 col-xs-12">
  <form id="form-post" action="" method="post" accept-charset="utf-8">
    <fieldset>
      <input name="pst[id]" type="hidden" value="<?= @$data['id'] ?>">

      <?php $APP->printMsg() ?>
      <div class="row">
        <div class="col-sm-6 col-md-8">
          <div class="form-group">
            <label for="title">Title:</label>
            <input required="required" style="font-weight:bold" type="text" class="form-control" name="pst[title]" id="title" value="<?= @$data['title'] ?>" placeholder="Title here...">
            <p class="help-block small">The title or heading of the message</p>
          </div>
        </div>
        <div class="col-sm-6 col-md-4">
          <div id="slug-box" class="form-group">
            <label class="control-label" for="slug">URL: <small><?= HOME_DIR ?>blog/<span id="slug-label"><?= @$data['slug'] ? $data['slug'] . '/' : '...' ?></span></small></label>
            <?php if (@$data['id']) : ?>
              <a target="_blank" class="btn btn-info btn-block" href="<?= HOME_DIR . 'blog/' . @$data['slug'] . '/' ?>">View Post</a>
            <?php else : ?>
              <input required="required" type="text" class="form-control" name="pst[slug]" id="slug" value="<?= @$data['slug'] ?>" <?= @$data['id'] ? 'disabled="disabled"' : '' ?> placeholder="slug-here">
            <?php endif; ?>
            <span id="slug-status-icon" class="glyphicon form-control-feedback" aria-hidden="true"></span>
            <p class="help-block small">The web address/URL of this post</p>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <div id="summernote"><?= @$data['content'] ?></div>
          <textarea name="pst[content]" style="display:none" hidden="hidden" id="content"><?= @$data['content'] ?></textarea>
        </div>
      </div>
      <script>
        $(document).ready(function() {
          $('#summernote').summernote({
            height: 300,
            placeholder: 'Content here...',
            focus: true
          });
        });
      </script>
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="category">Post Category</label>
            <select class="form-control" name="pst[category_id]" id="category">
              <?php
              $selected = @$data['category_id'];
              require_once(LIB_PATH . 'fns.php');
              print_category_option(0, '', $selected);
              ?>
            </select>
            <p class="help-block small">Post will be visible from this category and all parents of the category</p>
          </div>
          <div class="form-group">
            <label for="type">Post Type</label>
            <select class="form-control" name="pst[type]" id="type">
              <option value="blog" <?= @$data['type'] == 'blog' ? ' selected="selected"' : '' ?>>Blog/Article (interactive)</option>
              <option value="page" <?= @$data['type'] == 'page' ? ' selected="selected"' : '' ?>>Page (static information)</option>
            </select>
            <p class="help-block small">Whether blog or a page</p>
          </div>
          <div class="form-group">
            <label for="published">
              <input type="checkbox" name="pst[published]" id="published" value="1" <?= @$data['published'] ? 'checked' : '' ?>>
              Publish post
            </label>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label for="photo">Post thumbnail image:</label>
            <a id="image" class="btn btn-info" data-toggle="modal" href="#upload_modal" onclick="upload('image')">Select thumb image</a>
            <input id="file_input_image" name="pst[image]" type="hidden" value="<?php echo (isset($data['image']) && $data['image'] ? $data['image'] : ''); ?>" />
            <p><span id="file_status_image"><?php echo (isset($data['image']) && $data['image'] ? 'Image Loaded' : 'No image selected'); ?></span></p>
            <img src="<?php echo (isset($data['image']) && $data['image'] ? $data['image'] : ''); ?>" alt="No image" class="img-responsive" id="file_thumb_image" />
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-success btn-lg">Save</button>
      <input type="hidden" name="issubmit" value="1" />
    </fieldset>
  </form>
</div>
<!-- </div>
  </div>
</div> -->
<?php
$APP->assign('file_info_id', (isset($data['id']) ? $data['id'] : ''));
$APP->assign('file_info_category', 'post_thumbs');
$APP->assign('file_info_tag', 'image');
include(GLOBAL_PATH . 'contents/file_uploader.php');
?>
<script type="text/javascript">
  $(function() {
    var HOME = '<?= HOME_DIR ?>';
    $('#title, #slug').change(function(e) {
      //Show waiting sign
      var placeholder = $('#slug').attr('placeholder');
      var current = $('#slug').val();
      $('#slug').val('')
      $('#slug').attr('placeholder', 'Processing...');
      $('#slug').attr('title', 'Processing post URL');
      $('#slug-box').addClass('has-feedback').removeClass('has-success').removeClass('has-error').addClass('has-warning');
      $('#slug-status-icon').removeClass('glyphicon-ok').removeClass('glyphicon-warning-sign').addClass('glyphicon-hourglass');
      var title = $(this).val();
      $.get(HOME + 'post_slug/', {
        title: title
      }, function(data) {
        var result = $.parseJSON(data);
        $('#slug').attr('placeholder', placeholder);
        if (result.status == 'ok') {
          $('#slug').val(result.slug);
          $('#slug-label').text(result.slug + '/');
          $('#slug').attr('title', 'Post url validated successfully');
          $('#slug-box').addClass('has-feedback').removeClass('has-error').removeClass('has-warning').addClass('has-success');
          $('#slug-status-icon').removeClass('glyphicon-warning-sign').removeClass('glyphicon-hourglass').addClass('glyphicon-ok');
        } else {
          $('#slug').val(current);
          $('#slug').attr('title', 'Could not validate url, error: ' + result.error);
          $('#slug-box').addClass('has-feedback').removeClass('has-success').removeClass('has-warning').addClass('has-error');
          $('#slug-status-icon').removeClass('glyphicon-ok').removeClass('glyphicon-hourglass').addClass('glyphicon-warning-sign');
        }
      });
    });
    $('#form-post').submit(function(e) {
      $('#content').val($('#summernote').summernote('code'));
    });
  });
</script>