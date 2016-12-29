define([], function(){
	var util = {};
		util.formatDate = function(tDate){
			console.log(tDate);
			var monthNames = ["January", "February", "March", "April", "May", "June",
				"July", "August", "September", "October", "November", "December"
			];
			var month = monthNames[tDate.getMonth()]
			var day = tDate.getDate();
			var year = tDate.getFullYear();
			var nDate = month + ' ' + day + ', ' + year;
			
			return nDate;
		}
	return util;
});