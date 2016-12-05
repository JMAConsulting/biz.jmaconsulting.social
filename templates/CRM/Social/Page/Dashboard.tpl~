<div class="civisocial-wrapper">
  {if !isset($facebookPageConnected) && !isset($twitterConnected) }
    <div class="civisocial-box">
      <div class="box-item">
        No social network has been connected. Go to <a href="{crmURL p='civicrm/admin/civisocial/networks}">Network Settings</a> and connect one or more social networks.
      </div>
    </div>
  {else}
    {if $facebookPageConnected eq '1'}
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
    {/if}

    {if $twitterConnected eq '1'}
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
    {/if}

    <div class="container make-post">
      <h2>Post an update</h2>
      <form id="make-post" action="#" method="post">
          <textarea id="post-content" name="post_content"></textarea>
          <div class="container post-to">
              <div>Post to:</div>
              {if $facebookPageConnected eq '1'}
                <div><label><input id="post-to-facebook" type="checkbox" name="facebook" checked> Facebook</label></div>
              {/if}
              {if $twitterConnected eq '1'}
              <div><label><input id="post-to-twitter" type="checkbox" name="twitter" checked> Twitter</label></div>
              {/if}
              <div><span id="chars-left">{if $twitterConnected eq '1'}140{else}500{/if}</span> chars left</div>
          </div>
          <div class="container">
              <input id="post-button" class="crm-form-submit" type="submit" name="submit" value="Post">
          </div>
      </form>
    </div>

    <!-- @todo : SOCIAL FEED -->
    <!-- <div class="container">
      <div class="activity">
        <div class="logo bg-twitter"></div>
        <div class="message">
          <a href="#">Someone</a> is now following you.
          <ul class="actions">
            <li><a href="#">Follow</a></li>
            <li><a href="#">Tweet</a></li>
          </ul>
        </div>
      </div>

      <div class="activity">
        <div class="logo bg-facebook"></div>
        <div class="message">
          <a href="#">Someone</a> posted on your page
          <ul class="actions">
            <li><a href="#">See Post</a></li>
          </ul>
        </div>
      </div>

      <div class="activity">
        <div class="logo bg-twitter"></div>
        <div class="message">
          <a href="#">Dilip Raj Baral</a> is now following you.
          <ul class="actions">
            <li><a href="#">Follow</a></li>
            <li><a href="#">Tweet</a></li>
          </ul>
        </div>
      </div>
    </div> -->
  {/if}
</div>