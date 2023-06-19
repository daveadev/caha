define([], function(){
	var util = {};
		util.formatDate = function(tDate){
 			const options = { month: 'short', day: 'numeric', year: 'numeric' };
  			const formattedDate = tDate.toLocaleString('en-US', options);
			return formattedDate;
			
		}
		util.formatMoney = function(number){
			const options =  {style: 'currency',currency: 'PHP'};
			const formattedNumber = number.toLocaleString('en-US',options);
 			return formattedNumber;
		}

	return util;
});