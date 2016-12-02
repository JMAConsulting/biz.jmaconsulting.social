<div class="civisocial-wrapper">
  <div class="login-status bg-{$oAuthProvider}" style="position: relative;">
    <a class="picture" href="{$profileUrl}"><img src="{$pictureUrl}"></a>
    <p>
    	Logged in via {$oAuthProvider|capitalize} as <a target="_blank" href="{$profileUrl}">{$name}</a>.
    </p>
    <p class="logout">
    	<a href="{crmURL p='civicrm/civisocial/logout}?continue={$currentUrl}">Log Out</a>
    </p>
  </div>
</div>
