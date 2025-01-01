define(['app','util','api'],function(app,util) {
	const FEES ={};
	const INI_FEES = [
			{id:'REG', name:'Registration', amount:1750},
			{id:'ANN', name:'Annual School Fee', amount:1750},
			{id:'TUI', name:'Tuition & Other Fees', amount:1750},
			{id:'ENE', name:'Energy Fee', amount:1750},
			{id:'LER', name:'Learning Material', amount:1750},
	];
	FEES.items = INI_FEES;
	return FEES;
});