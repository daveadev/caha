"use strict";
define(['app','api','atomic/bomb','caha/api'],function(app){
	const DATE_FORMAT = "yyyy-MM-dd";
	let NEXT_SY = false;
	app.register.controller('BatchAdjustController',['$scope','$sce','$rootScope','$filter','$timeout','api','aModal','Atomic','cahaApiService',
	function($scope,$sce,$rootScope,$filter,$timeout,api,aModal,atomic,cahaapi){
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
				$scope.YearLevels = atomic.YearLevels;
				$scope.Sections = atomic.Sections;
				$scope.ActiveYearLevel = $scope.YearLevels[0].id;
			});
			$scope.TransactCodes = [
					{id:'ACEC', name:'AC/EC'},
				];
			$scope.PrevHeaders = ['Student', 'Amount'];
			$scope.PrevProps = ['student_name', 'amount'];
			$scope.PrevData = [
					{student_name:'Juan Dela Cruz',amount:999}
				];
			
			$scope.PrevInputs = [{field:'student_name',disabled:true, enableIf:'OTHRS'},{field:'amount',type:'number'}];
			$scope.updateItems = function(data){
				console.log(data);
			}

		}
	}]);

});