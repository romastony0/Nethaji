<!-- Modal -->
<div class="modal fade" id="edit_question_Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <!--<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>-->
	  <form id="edit_question_form">
      <div class="modal-body">
		<input type="hidden" id="edit_question_id" name="edit_question_id" value="">  
		<input type="hidden" id="edit_question_mtutor_id" name="edit_question_mtutor_id" value="">  
		<div class="form-group">
			<textarea id="edit_question_value" name="edit_question_value" rows="4" cols="50" ></textarea>
		</div>
      </div>
	  </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="save_edit_question" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>