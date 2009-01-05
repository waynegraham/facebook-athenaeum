{include file="header.tpl" title="Search Library"}

<div style="padding: 10px">
	Thanks for adding {$app_name}!  
	We've tried to integrate some of popular, and newest services with Facebook. 
	If you have questions, comments, bug reports, or feature requests, send the 
	developers a message from the 
	<a href="http://apps.facebook.com/apps/application.php?api_key={php}echo($GLOBALS['facebook_config']['api_key']){/php}">about</a> page.
</div>

<div id="canvasPage">
		<div class="search">
			<form action="{$callback}searchResults" method="post" id="search"> 
				<input type="text" name="q" size="46" /><br/>
				{if $search.CATALOG neq ""}
				<input type="submit" value="Catalog" name="Catalog" />
				{/if}
				{if $search.WEBSITE neq ""}
				<input type="submit" value="Website" clickrewriteid="results" clickrewriteurl="{$callback}searchResults" clickrewriteform="search" />
				{/if}
				{if $search.DATABASE neq ""}
				<input type="submit" value="Databases" name="Databases" />
				{/if}
			</form>
		</div>
			
		<div id="results">
			<div id="resultLeft">
				<h3>News:</h3>
				{rss file=$RSSFeed}
					<a href="{$rss_item.link}" target=_blank> {$rss_item.title} </a><br />
				{/rss}
			</div>
			<div id="resultRight">
			</div>
		</div>
</div>
{include file="footer.tpl"}
