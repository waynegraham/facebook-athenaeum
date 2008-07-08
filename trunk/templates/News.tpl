{include file="header.tpl" title="News"}

<h3>News</h3>

{rss file=$RSSFeed}
	<a href="{$rss_item.link}" target=_blank> {$rss_item.title} </a><br />
{/rss}

{include file="footer.tpl"}