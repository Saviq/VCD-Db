{literal}
var Trans = {
	items: {
{/literal}{foreach from=$itemJavascriptKeys item=i key=k}
"{$k}":"{$i}",
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