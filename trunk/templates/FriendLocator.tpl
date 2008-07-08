{include file="header.tpl" title="Friend Locator"}

<fb:js-string var="setBanner"><fb:success><fb:message>Location Set Successfully</fb:message>Your location will remain set for 48 hours.</fb:success></fb:js-string> 
<fb:js-string var="setClear"><fb:success><fb:message>Location Cleared Successfully</fb:message>Your location has been cleared.</fb:success></fb:js-string>
<fb:js-string var="clearText"><img src="{$callback}styles/images/stop.png">Clear My Location</fb:js-string>

<script type="text/javascript">
{if $myLoc.floor eq $floor}
	{literal}
	
	document.getElementById("locatorImage").removeClassName("hidden");
	var locX = {/literal}{$myLoc.x}{literal} + 2;
	var locY = {/literal}{$myLoc.y}{literal} - 40 + document.getElementById("mapImage").getAbsoluteTop();
	document.getElementById("locatorImage").setStyle({'position': 'absolute', 'left' : locX+'px', 'top': locY+'px'});
	{/literal}
{/if}

{foreach from=$friend item=location}
{literal}
	addImage({/literal}{$location.x}, {$location.y}{literal});
{/literal}
{/foreach}

{literal}

function addImage(x, y){
	 var box = document.getElementById('friends');
	 var a = document.createElement('div');
	 box.appendChild(a);
	 
	 x = x + 2;
	 y = y - 40 + document.getElementById("mapImage").getAbsoluteTop(); 
	 
	 var flagFriend = '<img src="{/literal}{$callback}{literal}styles/images/flag.png" style="position:absolute;top:'+y+'px;left:'+x+'px;" />';
	
	 box.setInnerXHTML(flagFriend);
}


function showPosition(e) {
	var ajax = new Ajax(); 
	var x = e.pageX - document.getElementById("mapImage").getAbsoluteLeft();
	var y = e.pageY - document.getElementById("mapImage").getAbsoluteTop();
	ajax.requireLogin = true;
	var queryParams = {"floor" : {/literal}{$floor}{literal}, "x" : x, "y" : y };
	var adjY = e.pageY - 40;
	var adjX = x + 2;
	ajax.post('{/literal}{$callback}/setLocation{literal}', queryParams);
	document.getElementById("banner").setInnerFBML(setBanner);
	document.getElementById("locatorImage").removeClassName("hidden");
	document.getElementById("locatorImage").setStyle({'position': 'absolute', 'left' : adjX+'px', 'top': adjY+'px'});
	document.getElementById("clearLocation").setInnerFBML(clearText);
}

function clearPosition() {
	var ajax = new Ajax();
	ajax.requireLogin = true;
	ajax.post('{/literal}{$callback}/clearLocation{literal}');
	document.getElementById("locatorImage").addClassName("hidden");
	document.getElementById("banner").setInnerFBML(setClear);
	document.getElementById("clearLocation").setTextValue('');
}

if(document.getElementById("mapImage")){
	document.getElementById("mapImage").addEventListener('click', showPosition, false);
} 
if(document.getElementById("clearLocation")){
	document.getElementById("clearLocation").addEventListener('click', clearPosition, false);
}
{/literal}


</script>

{foreach from=$maps item=map key=key name=props}
	{if $floor neq $key}
		<a href="http://apps.facebook.com/{$canvas}/FriendLocator/f{$key}">
	{/if}
	{$map.name}{if $floor neq $key}</a>&nbsp;&nbsp;{else}&nbsp;&nbsp;{/if}
	{if not $smarty.foreach.props.last}
		|&nbsp;&nbsp;
	{/if}
{/foreach}

<br /><br />
<div id="friends"></div>
<image class="locator hidden" src="{$callback}styles/images/user.png" id="locatorImage" />
<image class="map" src="{$maps.$floor.map}" id="mapImage" />

<div id="banner"></div>
<div id="clearLocation">{if $myLoc.floor eq $floor}<img src="{$callback}styles/images/stop.png">Clear My Location{/if}</div>
{include file="footer.tpl"}
