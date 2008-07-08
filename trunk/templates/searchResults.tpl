<div align="left">

{if $results eq ""}
<p>Your search returned 0 results.</p>
{else}
<ul>
{foreach from=$results item=Result}
	<li><a href="{$Result.U}">{$Result.T}</a></li>
{/foreach}
</ul>
{/if}
</div>