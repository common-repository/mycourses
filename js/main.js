Date.format = 'yyyy-mm-dd';

$(document).ready(function()
{
	$('.date-pick').datePicker({clickInput:true});
	$('#start_date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#end_date').dpSetStartDate(d.addDays(1).asString());
			}
		}
	);
	$('#end_date').bind(
		'dpClosed',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				$('#start_date').dpSetEndDate(d.addDays(-1).asString());
			}
		}
	);
	$('a.add-day').click(function() {
		last_time = $('tr.time').eq($('tr.time').length-1);
		last_time.after(last_time.clone());
		return false;
	})
});