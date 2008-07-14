<fb:tabs>
	{foreach from=$tabsMenu item=Item}
		<fb:tab_item href="/{$canvas}/{$Item|replace:' ':''}" title="{$Item}" align="left" {if $Title eq $Item} selected="true" {/if} />
	{/foreach}
</fb:tabs>
