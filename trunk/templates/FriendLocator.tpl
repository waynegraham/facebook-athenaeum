{include file="header.tpl" title="Friend Locator"}

<fb:js-string var="setBanner"><fb:success><fb:message>Location Set Successfully</fb:message>Your location will remain set for {$resetTime} hours.</fb:success></fb:js-string> 
<fb:js-string var="setClear"><fb:success><fb:message>Location Cleared Successfully</fb:message>Your location has been cleared.</fb:success></fb:js-string>
<fb:js-string var="clearText"><img src="{$callback}styles/images/stop.png">Clear My Location</fb:js-string>

{foreach from=$friend item=friendImage}
	<fb:js-string var="friend.friend_{$friendImage.uid}"><fb:profile-pic uid="{$friendImage.uid}" size="square" linked="true" /><fb:name uid="{$friendImage.uid}" /></fb:js-string>
{/foreach}

<script type="text/javascript">

var Xoffset = 3;
var Yoffset = -57;

{if $myLoc.floor eq $floor}
	{literal}
	document.getElementById("locatorImage").removeClassName("hidden");
	var locX = {/literal}{$myLoc.x}{literal} + Xoffset;
	var locY = {/literal}{$myLoc.y}{literal} + Yoffset + document.getElementById("mapImage").getAbsoluteTop();
	document.getElementById("locatorImage").setStyle({'position': 'absolute', 'left' : locX+'px', 'top': locY+'px'});
	{/literal}
{/if}

{foreach from=$friend item=location}
{literal}
	addImage({/literal}{$location.x}, {$location.y}, {$location.uid}{literal});
{/literal}
{/foreach}

{literal}
function addImage(x, y, friendid){
	 var box = document.getElementById('friends');
	 var a = document.createElement('div');
	 box.appendChild(a);
	 
	 x = x + Xoffset;
	 y = y + Yoffset + document.getElementById("mapImage").getAbsoluteTop(); 
	 
	 var flagFriend = '<img src="{/literal}{$callback}{literal}styles/images/flag.png" id="friend_'+friendid+'" style="position:absolute;top:'+y+'px;left:'+x+'px;" />';
	
	 a.setInnerXHTML(flagFriend);
	 document.getElementById('friend_'+friendid).addEventListener('mouseover', showUser, false);
	
	//  Uncomment the following line to have the friends picture dissapear when you move the mouse off of their flag.
	//  document.getElementById('friend_'+friendid).addEventListener('mouseout', showUser, false);
}

function showUser(e){
	location = document.getElementById('banner');
	if(e.type=="mouseout")
	{
		location.setTextValue('');
	} else {
		eventFiredBy_ObjectId = e.target.getId(); 
		location.setInnerFBML(friend[eventFiredBy_ObjectId]);
	}
}

function showPosition(e) {
	var ajax = new Ajax(); 
	var x = e.pageX - document.getElementById("mapImage").getAbsoluteLeft();
	var y = e.pageY - document.getElementById("mapImage").getAbsoluteTop();
	ajax.requireLogin = true;
	var queryParams = {"floor" : {/literal}{$floor}{literal}, "x" : x, "y" : y, "oldfloor": {/literal}{$myLoc.floor}{literal} };
	ajax.post('{/literal}{$callback}/setLocation{literal}', queryParams);
	var adjY = e.pageY + Yoffset;
	var adjX = x + Xoffset;
	
	var attach = {'caption':'{*actor*} shared a location in {/literal}{$shortName}{literal}.','media':[{'type':'image','src':'{/literal}{$imageURL}{literal}','href':'http://apps.facebook.com/swemtools/'}]};

    Facebook.streamPublish('', attach);
	
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
<div id="floors">
{foreach from=$maps item=map key=key name=props}
	{if $floor neq $key}
		<a href="/{$canvas}/FriendLocator/f{$key}">
	{/if}
	{$map.name}{if $floor neq $key}</a>&nbsp;&nbsp;{else}&nbsp;&nbsp;{/if}
	{if not $smarty.foreach.props.last}|&nbsp;&nbsp;{/if}
{/foreach}

</div>
<div id="addProfile">
	<fb:add-section-button section="profile" />
</div>

<br /><br />
<div id="friends"></div>
<image class="locator hidden" src="{$callback}styles/images/user.png" id="locatorImage" />
<image class="map" src="{$maps.$floor.map}" id="mapImage" />
<div id="bottom">
	<div id="banner"></div>
	<div id="clearLocation">{if $myLoc.floor eq $floor}<img src="{$callback}styles/images/stop.png">Clear My Location{/if}</div>
</div>
{include file="footer.tpl"}

