{if $fbEventEnabled eq '1'}
  {if $fbConnected eq '1'}
    <table style="display: none;">
      <tr id='facebook_event_url_row'>
        <td class="label">{$form.facebook_event_url.label}</td>
        <td>{$form.facebook_event_url.html|crmAddClass:huge}
          <a id="fetch_fb_event_info" href="javascript:void(0);">Fetch Info</a><br/>
          <span class="description">{ts}Please ensure that the Facebook event is public.{/ts}</span>
        </td>
      </tr>
    </table>
  {else}
    <div id="integrate-facebook" class="help">
      <a href="{crmURL p="civicrm/civisocial/login/facebook"}?continue={$currentUrl}">Login with Facebook</a> to integrate Facebook event.
    </div>
  {/if}
{/if}
{literal}
  <script type="text/javascript">
{/literal}
{if $fbEventEnabled eq '1'}
  {if $fbConnected eq '1'}
    {literal}
      cj('tr#facebook_event_url_row').insertBefore('tr.crm-event-manage-eventinfo-form-block-title');
    {/literal}
  {else}
    {literal}
      cj('div#integrate-facebook').insertBefore('.crm-block table');    
    {/literal}
  {/if}
{/if}
{literal}
  </script> 
{/literal}
