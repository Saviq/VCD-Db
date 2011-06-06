var jxBase = '{$jsBase}';
{literal}
var Trans = {
	items: {
{/literal}{foreach from=$itemJavascriptKeys item=i key=k name=js}
{if $smarty.foreach.js.last}
"{$k}":"{$i}"
{else}
"{$k}":"{$i}",
{/if}
{/foreach}{literal}	},
	late: function(string) {
		var str = this.items[string];
		if (!(this.items[string]===undefined)) {
			return this.items[string];
		} else {
			return 'Invalid translation key!';
		}
	}
};
{/literal}