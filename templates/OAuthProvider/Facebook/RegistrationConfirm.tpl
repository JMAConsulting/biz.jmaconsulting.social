<div id="fb-event-rsvp-option">
  <div class="spacer"></div>
  <div class="crm-group">
    <div class="header-dark">Facebook</div>
    <div class="crm-section no-label">
      <div class="content"><input type="checkbox" name="facebook_rsvp_event" checked="checked"> {$form.facebook_rsvp_event.label}</div>
    </div>
  </div>
  <div class="spacer"></div>
</div>
{literal}
<script type="text/javascript">
  cj('div#fb-event-rsvp-option').insertBefore('div.continue_message-section');
</script>
{/literal}