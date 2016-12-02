CRM.$(function($) {
  var maxId = 0, sinceId = 0;

  $('#tweets-next').click(function() {
    if (maxId == 0) {
      getTweets();
    }
    else {
      getTweets({'max_id' : maxId});
    }
  });

  $('#tweets-prev').click(function() {
    getTweets({'since_id' : sinceId});
  });

  $('#notif-next').click(function() {
    getNotifs({'next' : notifNext});
  });

  $('#notif-prev').click(function() {
    getNotifs({'prev' : notifPrev});
  });

  function getTweets(postData) {
    postData = postData || {};
    showLoader($('#tweets'));

  	CRM.api3('CivisocialUser', 'gettwitterfeed', postData).done(function(result) {
      if (!result.is_error) {
        processAjaxResult('tweets', result.values.data, postData);

        var tweets = result.values.data;
        for (var i = 0; i < tweets.length; i++) {
          var tweet = tweets[i];
          var quotedStatusHtml = '';

          if (typeof tweet.quoted_status != 'undefined') {
            var quotedStatus = tweet.quoted_status;
            quotedStatusHtml = '' +
               '<div class="activity quoted-status">' +
                '<div class="avatar">' +
                  '<a target="_blank" href="http://twitter.com/' + quotedStatus.user.screen_name + '"><img src="' + quotedStatus.user.image + '"></a>' +
                '</div>' +
                '<div class="message">' +
                  '<span class="posted-by"><a href="http://twitter.com/' + quotedStatus.user.screen_name + '">' + quotedStatus.user.name + '</a></span>' +
                  '<span class="activity-status">' + quotedStatus.time + '</span>' +
                  quotedStatus.text + 
                '</div>' +
              '</div>'; 
          }

          var postHtml = '' +
            '<div class="activity">' +
              '<div class="avatar">' +
                '<a target="_blank" href="http://twitter.com/' + tweet.user.screen_name + '"><img src="' + tweet.user.image + '"></a>' +
              '</div>' +
              '<div class="message">' +
                '<span class="posted-by"><a href="http://twitter.com/' + tweet.user.screen_name + '">' + tweet.user.name + '</a></span>' +
                tweet.text + 
                quotedStatusHtml +
                '<span class="activity-status">' + tweet.time + '</span>' +
                '<ul class="actions">' +
                  '<li><a target="_blank" href="https://twitter.com/intent/tweet?in_reply_to=' + tweet.id +'">Reply</a></li>' +
                  '<li><a target="_blank" href="https://twitter.com/intent/retweet?tweet_id=' + tweet.id +'">Retweet</a></li>' +
                  '<li><a target="_blank" href="https://twitter.com/intent/like?tweet_id=' + tweet.id +'">Like</a></li>' +
                '</ul>' +
              '</div>' +
            '</div>';
          
          $('#tweets').append(postHtml);
        }

        maxId = result.values.max_id;
        sinceId = result.values.since_id;
        hideLoader($('#tweets'));
      }
    });
  }

  function processAjaxResult(resultType, data, postData) {
    var nextBtn = $('#' + resultType + '-next').parent();
    var prevBtn = $('#' + resultType + '-prev').parent();

    if (data.length === 0) {
      if ('next' in postData) {
        $(nextBtn).hide();
      }
      else {
        $(prevBtn).hide();
      }
      return;
    }

    if (!$(nextBtn).is(':visible')) {
      $(nextBtn).show();
    }
    if (!$(prevBtn).is(':visible')) {
      $(prevBtn).show();
    }

    $('#' + resultType).empty();
  }

  // Get feed
  getTweets();
});
