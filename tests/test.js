"use strict";
define(['model'],function($model){
	return new $model(
			{
				meta:{
					title: 'Test title',
				},
				data:[
					{id:1,title:'Sample 1',description:'Sample description'},
					{id:2,title:'Sample 2',description:'Sample  second description'},
				]
			}
		);
		//test.setMeta({title:'Test'});
		//test.setData([{title:'Sample','description':'dasd'}]);
		/*
		test.GET = function(){
			return {success:test.list()};
		}
		test.POST = function(data){
			return {success:test.save(data)};
		}
		*/
});