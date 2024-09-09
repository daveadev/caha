"use strict";
define(['app','api','atomic/bomb'],function(app){
	const DATE_FORMAT = "yyyy-MM-dd";
	let NEXT_SY = false;
	app.register.controller('BillingController',['$scope','$rootScope','$filter','$timeout','api','aModal','Atomic',
	function($scope,$rootScope,$filter,$timeout,api,aModal,atomic){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
			$rootScope.$watch('_APP',function(app){
				if(!app) return;
				$scope.ActiveSY = $rootScope._APP.ACTIVE_SY;	
				NEXT_SY = $rootScope._APP.MOD_ESP;
			});
			
			atomic.ready(function(){
				var sys = atomic.SchoolYears;
					sys = $filter('orderBy')(sys,'-id');
					sys = [sys[0]];
				var sy = atomic.ActiveSY;
				if(NEXT_SY){
					var asy = sy +1;
					var nsy ={};
						nsy.id =  asy;
						nsy.label = asy + '-'+ (asy+1);
						nsy.code =  (asy+''.substring(2));
					sys.push(nsy);
				}

				$scope.SchoolYears = $filter('orderBy')(sys,'-id');
				$scope.ActiveSY = sy;
				
			});
		}
	}]);

});