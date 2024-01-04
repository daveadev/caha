define(['app','util','api'],function(app,util) {
	const BKLT ={};
	var api,booklets,activeBooklet;
	(function(){
		booklets = [];
	})();
	BKLT.link = function(_api){
		api =_api;
	}
	BKLT.util = util;
	BKLT.getBooklets = function(){
		return booklets;
	}
	BKLT.setActiveBL = function(bklt_id){
		booklets.map(function(bklt){
			if(bklt_id==bklt.id){
				bklt.series_no = bklt.receipt_type+ ' '+bklt.series_counter;
				activeBooklet = bklt;
			}
		});
		return activeBooklet;
	}
	BKLT.requestBooklets = function(docType){
		let filter ={
			receipt_type: docType,
			status:['ACTIV'],
			limit:'less'};
		let success =function(response){
			booklets = response.data;
		};
		let error =function(){};
		return api.GET('booklets',filter,success,error);
	}
	return BKLT;
});