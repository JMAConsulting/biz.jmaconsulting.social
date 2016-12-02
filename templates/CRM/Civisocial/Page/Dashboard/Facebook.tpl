<div class="civisocial-wrapper">
  {if !isset($facebookPageConnected)}
    <div class="civisocial-box">
      <div class="box-item">
        Facebook Page is not connected. Go to <a href="{crmURL p='civicrm/admin/civisocial/networks}">Network Settings</a> to connect Facebook page.
      </div>
    </div>
  {else}
    <div class="container stats">
      <div class="civisocial-box-inline">  
        <div class="box-item">
          <div class="image">
            <img src="{$facebookPagePicture}">
            <div class="logo bg-facebook"></div>
          </div>
          <div class="content">
            <div class="name">{$facebookPageName}</div>
            <div><a href="{crmURL p='civicrm/admin/civisocial/network/disconnect/facebookpage'}?continue={$currentUrl}">Disconnect</a></div>
          </div>
        </div>
      </div>

      <div class="stat-box bg-facebook">
        <div class="stat">
          <div class="count">{$facebookNewLikeCount}</div>
          <div class="label">new likes</div>
        </div>
        <div class="stat">
          <div class="count">{$facebookFanCount}</div>
          <div class="label">total likes</div>
        </div>
      </div>
    </div>

    <div class="container make-post">
      <h2>Post an update</h2>
      <form id="make-post" action="#" method="post">
          <textarea id="post-content" name="post_content"></textarea>
          <input type="hidden" name="facebook" value="On">
          <div class="container post-to">
              <div><span id="chars-left">500</span> chars left</div>
          </div>
          <div class="container">
              <input id="post-button" class="crm-form-submit" type="submit" name="submit" value="Post">
          </div>
      </form>
    </div>
  </div>

  <div id="tabs">
    <ul>
      <li><a href="#feed-container">Feed</a></li>
      <li><a id="notif-label" href="#notif-container">Notifications</a></li>
    </ul>

    <div id="feed-container">
      <div id="feed">
        <!--<div class="activity">
          <div class="avatar">
            <img>
          </div>
          <div class="message">
            Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. <br/><br/>Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet. <span class="activity-status">2 months ago</span>
            <ul class="actions">
              <li><a href="#">See Post</a></li>
            </ul>
          </div>
        </div>-->
      </div>
      <div class="cursor">
        <ul>
          <li><a id="feed-prev" href="javascript:void(0);">&laquo;</a>
          <li><a id="feed-next" href="javascript:void(0);">&raquo;</a>
        </ul>
      </div>
    </div>
    <div id="notif-container">
      <div id="notif">
        <!-- <a target="_blank" href="#">
          <div class="activity">
            <div class="avatar">
              <img>
            </div>
            <div class="message">
              Someon commented on your post. 
              <span class="activity-status">2 hours ago</span>
            </div>
          </div>
        </a> -->
      </div>
      <div class="cursor">
        <ul>
          <li><a id="notif-prev" href="javascript:void(0);">&laquo;</a>
          <li><a id="notif-next" href="javascript:void(0);">&raquo;</a>
        </ul>
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