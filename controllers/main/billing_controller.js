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
				$scope.YearLevels = atomic.YearLevels;
				$scope.Sections = atomic.Sections;
				$scope.ActiveYearLevel = $scope.YearLevels[0].id;
			});

			$scope.BillHeaders = [{class:'col-md-2',label:'Stud No.'},{class:'col-md-3',label:'Name'},'Section','Bill No.',{class:'col-md-2',label:'Due Amount'},{class:'col-md-2',label:'Paid Amount'},'Status'];
			$scope.BillProps = ['sno','student','section','billing_no','due_amount','paid_amount','status'];
			$scope.BillData=[];
		}
		$selfScope.$watch('BLC.ActiveSY',function(sy){
			if(!sy) return;
			$scope.getBillDetails();
		});
		$scope.filterBill = function(yObj){
			$scope.SearchPlaceHolder ='Search '+yObj.name;
			let fltrObj = {year_level_id:yObj.id};
			$scope.FilteredBillData=$filter('filter')($scope.BillData,fltrObj);
		}
		$scope.getBillDetails = function(){
			let data = {'limit':'less'};
			let success = function(response){
				$scope.BillData = response.data;
			};
			let error = function(response){
				alert(response.meta.message);

			};
			api.GET('billings',data,success,error);
		}
	}]);

});