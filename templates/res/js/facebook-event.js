CRM.$(function($) {
  var waitingForResponse = false;

  cj('#fetch_fb_event_info').click(function() {
    if (waitingForResponse) {
      return;
    }

    var match = cj('#facebook_event_url').val().match(/facebook\.com\/events\/([0-9]+)\/?/m);
    if (match) {
      eventId = match[1];
    }
    else {
      var label = cj('#facebook_event_url_row td.label label');
      cj(label).html(cj(label).html().replace(/<\/?[^>]+(>|$)/g, ""));
      cj(label).html('<span class="crm-error crm-error-label">' + cj(label).html() + '</span>');
      cj('#facebook_event_url').addClass('crm-error');

      if (!cj('#facebook_event_url_row td span.huge.crm-error').length) {
        cj('<span class="huge crm-error">Please enter a valid Facebook event URL</span>').insertAfter('#facebook_event_url');
      }

      return;
    }

    cj(this).html('Fetching..');
    waitingForResponse = true;

    CRM.api3('SocialUser', 'getfacebookeventinfo', {
      "event_id": eventId
    }).done(function(result) {
      if (result.is_error) {
        if (!cj('#facebook_event_url_row td span.huge.crm-error').length) {
          cj('<span class="huge crm-error">' + result.error_message + '</span>').insertAfter('#facebook_event_url');
        }
      }
      else {
        cj('#title').val(result.name);
        CKEDITOR.instances.description.setData(result.description);

        var startTime = new Date(result.start_time);
        var endTime = new Date(result.end_time);

        cj('[id^=start_date_display]').datepicker("setDate", startTime);
        cj('[id^=end_date_display]').datepicker("setDate", endTime);
        cj('#start_date_time').val(format12HourTime(startTime));
        cj('#end_date_time').val(format12HourTime(endTime));

        // The event is public
        cj('#is_public').prop('checked', true);
      }

      cj('#fetch_fb_event_info').html('Fetch Info');
      waitingForResponse = false;
    });
  });

  cj('#facebook_event_url').blur(function() {
    var label = cj('#facebook_event_url_row td.label label');
    cj(label).html(cj(label).html().replace(/<\/?[^>]+(>|$)/g, ""));
    cj('#facebook_event_url').removeClass('crm-error');

    var errorText = cj('#facebook_event_url_row td span.crm-error');
    if (errorText.length) {
      cj(errorText).remove();
    }
  });

  function format12HourTime(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes + ampm;
    return strTime;
  }
});
