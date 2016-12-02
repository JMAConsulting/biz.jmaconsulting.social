{* HEADER *}
<div class="crm-block crm-form-block crm-date-form-block">
	<div class="help">
	  Use this screen to configure apps for different social networks and enable them for user to connect with.
	</div>
  <div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="top"}</div>
  <fieldset>
    <legend>{ts}Facebook{/ts}</legend>
	  <div class="crm-section">
	    <div class="label">{$form.enable_facebook.label}</div>
	    <div class="content">{$form.enable_facebook.html}</div>
	    <div class="clear"></div>
	  </div>
	  <div class="crm-section">
	    <div class="label">{$form.facebook_api_key.label}</div>
	    <div class="content">{$form.facebook_api_key.html|crmAddClass:huge}</div>
	    <div class="clear"></div>
	  </div>
	  <div class="crm-section">
	    <div class="label">{$form.facebook_api_secret.label}</div>
	    <div class="content">{$form.facebook_api_secret.html|crmAddClass:huge}</div>
	    <div class="clear"></div>
	  </div>
	</fieldset>
	<fieldset>
    <legend>{ts}Google{/ts}</legend>
	  <div class="crm-section">
	    <div class="label">{$form.enable_googleplus.label}</div>
	    <div class="content">{$form.enable_googleplus.html}</div>
	    <div class="clear"></div>
	  </div>
  	  <div class="crm-section">
	    <div class="label">{$form.googleplus_api_key.label}</div>
	    <div class="content">{$form.googleplus_api_key.html|crmAddClass:huge}</div>
	    <div class="clear"></div>
	  </div>
	  <div class="crm-section">
	    <div class="label">{$form.googleplus_api_secret.label}</div>
	    <div class="content">{$form.googleplus_api_secret.html|crmAddClass:huge}</div>
	    <div class="clear"></div>
	  </div>
	</fieldset>
	<fieldset>
    <legend>{ts}Twitter{/ts}</legend>
	  <div class="crm-section">
	    <div class="label">{$form.enable_twitter.label}</div>
	    <div class="content">{$form.enable_twitter.html}</div>
	    <div class="clear"></div>
	  </div>
	  <div class="crm-section">
	    <div class="label">{$form.twitter_api_key.label}</div>
	    <div class="content">{$form.twitter_api_key.html|crmAddClass:huge}</div>
	    <div class="clear"></div>
	  </div>
	  <div class="crm-section">
	    <div class="label">{$form.twitter_api_secret.label}</div>
	    <div class="content">{$form.twitter_api_secret.html|crmAddClass:huge}</div>
	    <div class="clear"></div>
	  </div>
	</fieldset>
	<div class="crm-submit-buttons">{include file="CRM/common/formButtons.tpl" location="bottom"}</div>
</div>
