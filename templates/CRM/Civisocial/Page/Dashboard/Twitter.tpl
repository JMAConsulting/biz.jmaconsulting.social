<div class="civisocial-wrapper">
  {if !isset($twitterConnected)}
    <div class="civisocial-box">
      <div class="box-item">
        Twitter is not connected. Go to <a href="{crmURL p='civicrm/admin/civisocial/networks}">Network Settings</a> and connect Twitter.
      </div>
    </div>
  {else}
  	<div class="container stats">
      <div class="civisocial-box-inline">  
        <div class="box-item">
          <div class="image">
            <img src="{$twitterPicture}">
            <div class="logo bg-twitter"></div>
          </div>
          <div class="content">
            <div class="name">{$twitterName}</div>
            <div><a href="{crmURL p='civicrm/admin/civisocial/network/disconnect/twitter'}?continue={$currentUrl}">Disconnect</a></div>
          </div>
        </div>
      </div>

  		<div class="stat-box bg-twitter">
        <div class="stat">
          <div class="count">{$twitterFollowersCount}</div>
          <div class="label">followers</div>
        </div>
        <div class="stat">
          <div class="count">{$twitterFriendsCount}</div>
          <div class="label">friends</div>
        </div>
        <div class="stat">
          <div class="count">{$twitterFavoritesCount}</div>
          <div class="label">favourites</div>
        </div>
      </div>
    </div>

    <div class="container make-post">
      <h2>Post a Tweet</h2>
      <form id="make-post" action="#" method="post">
          <textarea id="post-content" name="post_content"></textarea>
          <input type="hidden" name="twitter" value="On">
          <div class="container post-to">
              <div><span id="chars-left">140</span> chars left</div>
          </div>
          <div class="container">
              <input id="post-button" class="crm-form-submit" type="submit" name="submit" value="Post">
          </div>
      </form>
    </div>
  </div>

  <div id="tabs">
    <ul>
      <li><a href="#tweets-container">Tweets</a></li>
      <li><a href="#followers">Followers</a></li>
    </ul>

    <div id="tweets-container">
      <div id="tweets">
        <!--<div class="activity">
          <div class="avatar">
            <img>
          </div>
          <div class="message">
            Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. <br/><br/>Lorem ipsum dolor sit amet. <span class="activity-status">2 hours ago</span>
            <ul class="actions">
              <li><a href="#">Reply</a></li>
              <li><a href="#">Retweet</a></li>
              <li><a href="#">Favorite</a></li>
            </ul>
          </div>
        </div> -->
      </div>
      <div class="cursor">
        <ul>
          <li><a id="tweets-prev" href="javascript:void(0);">&laquo;</a>
          <li><a id="tweets-next" href="javascript:void(0);">&raquo;</a>
        </ul>
      </div>
    </div>
    <div id="followers">
      <div class="container">
        Sort:
        <select id="sort-followers">
          <option value="most-active">Most active</option>
          <option value="alphabetical">Alphabetical</option>
        </select>
      </div>

      <div class="activity follower">
        <div class="avatar">
          <img>
        </div>
        <div class="info">
          <div class="name">Dilip Raj Baral</div>
          <ul class="actions">
            <li><a href="#">Tweet</a></li>
            <li><a href="#">View Details</a></li>
          </ul>
        </div>
      </div>

      <div class="activity follower">
        <div class="avatar">
          <img>
        </div>
        <div class="info">
          <div class="name">Dilip Raj Baral</div>
          <ul class="actions">
            <li><a href="#">Tweet</a></li>
            <li><a href="#">View Details</a></li>
          </ul>
        </div>
      </div>

      <div class="activity follower">
        <div class="avatar">
          <img>
        </div>
        <div class="info">
          <div class="name">Dilip Raj Baral</div>
          <ul class="actions">
            <li><a href="#">Tweet</a></li>
            <li><a href="#">View Details</a></li>
          </ul>
        </div>
      </div>

      <div class="activity follower">
        <div class="avatar">
          <img>
        </div>
        <div class="info">
          <div class="name">Dilip Raj Baral</div>
          <ul class="actions">
            <li><a href="#">Tweet</a></li>
            <li><a href="#">View Details</a></li>
          </ul>
        </div>
      </div>

      <div class="activity follower">
        <div class="avatar">
          <img>
        </div>
        <div class="info">
          <div class="name">Dilip Raj Baral</div>
          <ul class="actions">
            <li><a href="#">Tweet</a></li>
            <li><a href="#">View Details</a></li>
          </ul>
        </div>
      </div>

      <div class="activity follower">
        <div class="avatar">
          <img>
        </div>
        <div class="info">
          <div class="name">Dilip Raj Baral</div>
          <ul class="actions">
            <li><a href="#">Tweet</a></li>
            <li><a href="#">View Details</a></li>
          </ul>
        </div>
      </div>
    </div>

    {literal}
      <script>
        CRM.$(function($) {
          $('#tabs').tabs();
        });
      </script>
    {/literal}
  {/if}
</div>