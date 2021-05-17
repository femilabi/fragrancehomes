<div id="confirm-modal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-complete">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="box-title">Confirm action</h4>
            </div>
            <div id="confirm-modal-content" class="modal-body">
                Are you sure you want to proceed?
            </div>
            <div id="btns-box" class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                <button id="btn-confirm" type="button" class="btn btn-complete" data-dismiss="modal"><i class="fa fa-check"></i> Yes</button>
            </div>
        </div>
    </div>
</div>
<script>
function confirmAction(message, callback){
	var btn = $('#btn-confirm');
	$(btn).off('click');
	if(message.length) $('#confirm-modal-content').html(message); else $('#confirm-modal-content').text('Are you sure you want to proceed?');
	$(btn).one('click',function(e){
		callback();
	});
	$('#confirm-modal').modal('show');
}
</script>