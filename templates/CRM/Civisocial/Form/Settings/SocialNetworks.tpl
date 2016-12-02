<div class="crm-block crm-form-block crm-date-form-block civisocial-wrapper">
  <div class="civisocial-box">
    <div class="help">
      Use this screen to connect to your organization's accounts on social networks for social insight, facebook event integration and other features.
    </div>
  </div>

  {* Facebook Events Section *}
  <div class="civisocial-box">
    <div class="box-item">
      <label class="input">{$form.integrate_facebook_events.html} Integrate Facebook events</label>
    </div>
  </div>
  {* End Facebook Events Section *}

  {* Facebook Section *}
  <div class="civisocial-box">
    {if $facebookPageConnected eq '1'}
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
    {else}
      <div class="crm-section">
          <a class="btn btn-facebook bg-facebook" href="{crmURL p='civicrm/admin/civisocial/network/connect/facebookpage'}?continue={$currentUrl}">Connect Facebook Page</a>
      </div>
    {/if}
  </div>
  {* End Facebook Section *}

  {* Twitter Section *}
  <div class="civisocial-box">
    {if $twitterConnected eq '1'}
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
    {else}
      <div class="crm-section">
          <a class="btn btn-twitter bg-twitter" href="{crmURL p='civicrm/admin/civisocial/network/connect/twitter'}?continue={$currentUrl}">Connect Twitter</a>
      </div>
    {/if}
  </div>
  {* End Twitter Section *}
