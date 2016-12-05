<div class="crm-block crm-form-block crm-date-form-block civisocial-wrapper">
  <div class="civisocial-box">
    <div class="help">
      Select the Facebook page you want to connect with CiviCRM.
    </div>
    <div class="box-item">
      <form action="{$postUrl}" method="post">
        <div class="select">
          <select name="page_id">
            {foreach from=$pageList item=page}
              <option value="{$page.id}">{$page.name}</option>
            {/foreach}
          </select>
        </div>
        <div class="spacer"></div>
        <button class="btn btn-facebook bg-facebook" type="submit">Connect Page</button>
      </form>
    </div>
  </div>
</div>