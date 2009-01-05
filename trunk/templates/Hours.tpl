{include file="header.tpl" title="Hours"}
{if $admin eq 1}
{literal}
<script type="text/javascript">
function editHours(){
	document.getElementById('edit').setStyle("display", "none");
	document.getElementById('hours').setStyle("display", "none");
	document.getElementById('hoursForm').setStyle("display", "");
}

function submitHours(){
	var ajax = new Ajax();
	var newHours = document.getElementById("hoursData").getValue(); 
	ajax.requireLogin = true;
	var queryParams = {"hours": newHours };
	ajax.post('{/literal}{$callback}writeHours{literal}', queryParams);
	document.getElementById('save').setStyle("display", "none");
	document.getElementById('hoursForm').setTextValue(newHours + " -- Refresh for actual formatting");
	document.getElementById('edit').setStyle("display", "");
}

document.getElementById('edit').addEventListener('click', editHours, false);
document.getElementById('save').addEventListener('click', submitHours, false);
</script>
{/literal}

{/if}
<h3>Hours</h3>
<div id="hours">
{include file="hourData.tpl"}
</div>
{if $admin eq 1}
<div id="hoursForm" style="display:none">
	<textarea rows="5" cols="75" name="hoursData" id="hoursData">
{include file="hourData.tpl"}
	</textarea><br />
	<input type="button" name="Save" value="Save" id="save" />
</div>
<input type="button" name="Edit" value="Edit" id="edit" />
{/if}
{include file="footer.tpl"}
