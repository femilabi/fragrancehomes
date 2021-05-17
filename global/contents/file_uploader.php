<div class="modal fade file_uploader" id="upload_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <a class="close" data-dismiss="modal">×</a>
          <h3>Upload file</h3>
      </div>
      <div class="modal-body">
            <form id="upload" method="post" action="file_uploader/" enctype="multipart/form-data">
                <div id="drop">
                    Drop a file Here
                    <div>
                        <a>Browse</a>
                        <input type="file" name="upl" multiple accept="<?=$APP->get('file_info_id')?$APP->get('file_info_id'):'*'?>" />
                    </div>
                </div>
    
                <ul>
                    <!-- The file uploads will be shown here -->
                </ul>
                <input id="file_info_category" type="hidden" name="file_info[category]" value="<?php echo($APP->get('file_info_category')); ?>"/>
                <input id="file_info_id" type="hidden" name="file_info[id]" value="<?php echo($APP->get('file_info_id')); ?>"/>
                <input id="file_info_tag" type="hidden" name="file_info[tag]" value="<?php echo($APP->get('file_info_tag')); ?>"/>
            </form>
      </div>
      <div class="modal-footer">
          <a class="btn btn-success" data-dismiss="modal">Done</a>
      </div>
    </div>
  </div>
</div>