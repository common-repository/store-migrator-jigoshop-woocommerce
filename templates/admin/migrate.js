var $j = jQuery.noConflict();
$j(function() {

	// Opening Overview tab
	$j('#skip_overview').click(function(){
		$j('#skip_overview_form').submit();
	});

	// Select all field options
	$j('.checkall').click(function () {
		$j(this).closest('form').find(':checkbox:not(:disabled)').attr('checked', true);
	});

	// Unselect all field options
	$j('.uncheckall').click(function () {
		$j(this).closest('form').find(':checkbox:not(:disabled)').attr('checked', false);
	});

});