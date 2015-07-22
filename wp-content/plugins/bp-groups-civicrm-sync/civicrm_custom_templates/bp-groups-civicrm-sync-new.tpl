{* template block that contains the new field *}
<div id="testfieldoptions">
<h3>{ts}BuddyPress Group Sync{/ts}</h3>
<table class="form-layout-compressed">
<tr>
  <td colspan="2"><span style="color: red;">{ts}<strong>NOTE:</strong> If you are going to create a BuddyPress Group, you only need to fill out the "Title" field (and optionally the "Description" field). The Group Type will be automatically set to "Access Control" and the Parent Group will be automatically assigned to the container group.{/ts}</span></td>
</tr>
<tr>
  <td class="label"><label for="bpgroupscivicrmsynccreatefromnew">{ts}Create a BuddyPress Group{/ts}</label></td>
  <td>{$form.bpgroupscivicrmsynccreatefromnew.html}</td>
</tr>
</table>
</div>

{* reposition the above block after #someOtherBlock *}
<script type="text/javascript">
  // jQuery will not move an item unless it is wrapped
  cj('#testfieldoptions').insertBefore('.crm-group-form-block > .crm-submit-buttons:last');
</script>
