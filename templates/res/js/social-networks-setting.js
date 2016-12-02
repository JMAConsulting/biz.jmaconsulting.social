CRM.$(function($) {
	cj('#integrate_facebook_events').change(function() {
    var input = cj(this);
    var checked = input.prop('checked');
    input.prop('disabled', true);
    CRM.api3('Setting', 'create', {
      "integrate_facebook_events": checked
    }).done(function(result) {
      input.prop('disabled', false);
		});
	});

});
