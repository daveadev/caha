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

			$scope.BillHeaders = [{class:'col-md-2',label:'Stud No.'},{class:'col-md-3',label:'Name'},'Section','Billing No.',{class:'col-md-2 amount',label:'Due Amount'},{class:'col-md-2 amount' ,label:'Paid Amount'},'Status'];
			$scope.BillProps = ['sno','student','section','billing_no','due_amount_disp','paid_amount_disp','status'];
			$scope.BillData=[];
			$scope.SearchFields =['student', 'sno'];
			$scope.BillObj =null;
			$scope.BillObj =null;
		}
		$selfScope.$watch('BLC.ActiveSY',function(sy){
			if(!sy) return;
			$scope.getBillDetails();
		});
		$scope.filterBill = function(yObj){
			if(typeof yObj=='object'){
				let placeholderText = `Search ${yObj.name} students`;
				$scope.SearchPlaceHolder =[placeholderText,'Search by name or sno'];
				$scope.ActiveYearLevel =  yObj;
			}
			
			let fltrObj = {};
			if($scope.ActiveYearLevel)
				fltrObj.year_level_id =  $scope.ActiveYearLevel.id;

			
			$scope.FilteredBillData=$filter('filter')($scope.BillData,fltrObj);
		}
		
		$scope.clearSearch = function(){

		}
		$selfScope.$watch('BLC.SearchText',$scope.filterBill);

		$scope.showBillDetails = function(){
			$timeout(function(){
				let billNo = $scope.ActiveBillObj.billing_no;
				$scope.BillURL = 'api/reports/billings/'+billNo;
				aModal.open('BillingModal');
			},500);
			
			
		}
		$scope.closeModal = function(){
			$scope.BillObj =null;
			aModal.close('BillingModal');
		}
		$scope.getBillDetails = function(){
			let data = {'limit':'less'};
			let success = function(response){
				let billData =  response.data;
				for(var i in billData){
					let dispDueAmt = billData[i].due_amount;
						dispDueAmt = !dispDueAmt||dispDueAmt<=0?'-':$filter('currency')(dispDueAmt);
					let dispPayAmt = billData[i].paid_amount;
						dispPayAmt = !dispPayAmt||dispPayAmt<=0?'-':$filter('currency')(dispPayAmt);
					billData[i].due_amount_disp  =  dispDueAmt;
					billData[i].paid_amount_disp =  dispPayAmt;
					let mobile = billData[i].mobile;
					billData[i].mobile =  `0${mobile}`;
				}
				$scope.BillData = billData;
			};
			let error = function(response){
				alert(response.meta.message);

			};
			api.GET('billings',data,success,error);
		}
	}]);

});