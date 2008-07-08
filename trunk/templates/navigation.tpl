<fb:tabs>
	{foreach from=$tabsMenu item=Item}
		<fb:tab_item href="http://apps.facebook.com/{$canvas}/{$Item|replace:' ':''}" title="{$Item}" align="center" {if $Title eq $Item} selected="true" {/if} />
	{/foreach}
</fb:tabs>
