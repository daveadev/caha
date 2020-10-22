define(function() {
	function parse(str,aliases,addresses){
		var a = [];
		
		for(var alias in aliases)
			a.push('\\b'+alias+'\\b');
		for(var address in addresses)
			a.push('\\b'+address+'\\b');
		
		var regex =  new RegExp(a.join("|"),"g");
		var tokens = [];
		var match = str.match(regex);
		if(match) 
			tokens = match.filter(Boolean);
		return tokens;
	}
	function replaceAll(str,search,replacement){
		return str.replace(new RegExp('\\b'+search+'\\b',"g"),replacement);
	}
	return {
		parse:parse,
		replaceAll:replaceAll,
	}
 });