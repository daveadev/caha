define(['app','util','api'],function(app,util) {
	const FEES ={};
	const INI_FEES = [
			{id:'REG', name:'Registration', amount:1750, computation:'UPREG'},
			{id:'ANN', name:'Annual School Fee', amount:9880,computation:'UPNF'},
			{id:'TUI', name:'Tuition & Other Fees', amount:9900,computation:'DIST'},
			{id:'ENE', name:'Energy Fee', amount:1300,computation:'DIST'},
			{id:'LER', name:'Learning Material', amount:3952.30,computation:'UPNF'},
	];
	FEES.items = INI_FEES;
	return FEES;
});