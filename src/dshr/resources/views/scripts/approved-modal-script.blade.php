<script type="text/javascript">

	// CONFIRMATION DELETE MODAL
	$('#confirmApproved').on('show.bs.modal', function (e) {
		var message = $(e.relatedTarget).attr('data-message');
		var title = $(e.relatedTarget).attr('data-title');
		var form = $(e.relatedTarget).closest('form');
		$(this).find('.modal-body p').text(message);
		$(this).find('.modal-title').text(title);
		$(this).find('.modal-footer #confirm').data('form', form);
	});
	$('#confirmApproved').find('.modal-footer #confirm').on('click', function(){
		var write_remarks = $('#write_remarks').val();
		$("input[name$='remarks']").val(write_remarks);
	  	$(this).data('form').submit();
	});

</script>