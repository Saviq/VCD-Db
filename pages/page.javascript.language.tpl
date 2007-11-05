{literal}
var Trans = {
	items: {
		"konni":"rassagat"
	},
	
	late: function(string) {
		var str = this.items[string];
		if (!(this.items[string]===undefined)) {
			return this.items[string];
		} else {
			return 'No translation found!';
		}
	}
};
{/literal}
