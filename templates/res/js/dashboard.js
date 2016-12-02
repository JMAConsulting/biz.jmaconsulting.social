CRM.$(function($) {
  var xhrOn = false;
  var post_char_limit = parseInt($('#chars-left').html());

	$('#post-content').keyup(function() {
		updateCharsLeft();
	});

  $('#post-content').blur(function() {
    if ($(this).hasClass('error')) {
      $(this).removeClass('error');
    }

    if ($('#make-post > span.error').length) {
     $('#make-post > span.error').remove(); 
    }

    $('#post-button').val('Post');
  });

  $('#post-to-facebook').change(function() {
    if ($('#post-to-facebook').is(':checked') && !$('#post-to-twitter').is(':checked')) {
      post_char_limit = 500;
      updateCharsLeft();
    }
  });

  $('#post-to-twitter').change(function() {
    if ($('#post-to-twitter').is(':checked')) {
      post_char_limit = 140;
    } else {
      post_char_limit = 500;
    }
    updateCharsLeft();
  });

  $('#make-post').submit(function(e) {
    e.preventDefault();

    // Validate form inputs
    validateFields();

    if (xhrOn) {
      return;
    }

    var postData = {};
    $('#make-post').find('[name]').each(function() {
      if ($(this).is(':checkbox')) {
        if ($(this).is(':checked')) {
          postData[this.name] = $(this).is(':checked'); 
        }
      }
      else {
        postData[this.name] = this.value;  
      }
    });

    var xhrOn = true;
    $('#post-button').val('Posting..');

    CRM.api3('CivisocialUser', 'updatestatus', postData).done(function(result) {
      if (result.is_error) {
        showError(result.error_message);
      }
      else {
        $('#post-button').val('Posted!');
        $('#post-content').val('');
        updateCharsLeft();
      }
      xhrOn = false;
    });
  });

  function updateCharsLeft() {
    var post_char_left = post_char_limit - $('#post-content').val().length;
    $('#chars-left').html(post_char_left);
  }

  function validateFields() {
    // Begin validation
    if ($('#post-content').val().length === 0) {
      showError('Post cannot be empty.');
      return;
    }

    if ($('#chars-left').html() < 0) {
      showError('Please limit the post length to ' + post_char_limit + ' characters.');
      return;
    }

    if ($('#post-to-facebook').length && $('#post-to-twitter').length) {
      if (!$('#post-to-facebook').is(':checked') && !$('#post-to-twitter').is(':checked')) {
        showError('Please check at least one social network.');
        return;
      }
    }
  }

  function showError(message) {
    $('#post-content').addClass('error');
    if ($('#make-post > span.error')) {
      $('#make-post > span.error').remove();
    }
    $('<span class="error block">' + message + '</span>').insertAfter('#make-post > .post-to');
  }

  window.showLoader = function(elem) {
    var loaderHtml = '<div class="loader"></div>';

    $(elem).parent().css('position', 'relative');
    $(elem).parent().append(loaderHtml);
  };

  window.hideLoader = function(elem) {
    $(elem).parent().children('.loader').remove();
  };
});
