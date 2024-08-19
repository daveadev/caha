"use strict";
define(['app','transact','booklet','api','atomic/bomb'],function(app,TRNX,BKLT){
	const DATE_FORMAT = "yyyy-MM-dd";
	const TRNX_LIST = TRNX.__LIST;
	let NEXT_SY = false;
	app.register.controller('CashierAdminController',['$scope','$rootScope','$filter','$timeout','api','aModal','Atomic',
	function($scope,$rootScope,$filter,$timeout,api,aModal,atomic){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
			$rootScope.$watch('_APP',function(app){
				if(!app) return;
				$scope.ActiveSY = $rootScope._APP.ACTIVE_SY;	
				console.log($rootScope._APP);
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
			$scope.Headers = ['Description',{class:'col-md-4',label:'Amount'}];
			$scope.Props = ['description','disp_amount'];
			$scope.Inputs = [{field:'description',disabled:true, enableIf:'OTHRS'},{field:'amount',type:'number'}];
			$scope.PSTypes = [{id:'regular',name:'Current Account'},{id:'old', name:'Ext. Payment Plan'}];
			$rootScope.__PSType = 'regular';
			$scope.PSType = 'regular';
			$rootScope.hasMultiplePS = false;
			$scope.PSHeaders = ['Due Date', 'Due Amount','Status'];
			$scope.PSProps = ['disp_date','disp_amount','disp_status'];
			$scope.Paysched = [];
			$scope.StudFields = ['id','full_name','enroll_status','student_type','department_id','year_level_id','section','sno'];
			$scope.StudSearch = ['first_name','last_name','middle_name','sno','rfid'];
			$scope.StudDisplay = 'display_name';
			$scope.OthrFields = ['id','account_details','account_type'];
			$scope.OthrSearch = ['id','account_details'];
			$scope.OthrDisplay = 'account_details';
			$scope.OthrFilter = {account_type:"others"};
			$scope.TransacDetails=[];
			$scope.TotalAmount = 0;
			$scope.SeriesNo = '';
			$scope.PayeeType='STU'; 
			$scope.TransacDate = new Date();
			$scope.TotalDispAmount = TRNX.util.formatMoney($scope.TotalAmount);
		}

		$scope.saveInputs = function(){
			var trnxSeriesNo = $scope.SeriesNo;
			var trnxAmount = $scope.Amount;
			var trnxDate = $scope.TransactDate;
				trnxDateFormat = $filter('date')(new Date(trnxDate),DATE_FORMAT);
			var trnxSY = $scope.ActiveSY;
			var trnxData= {
				    "series_no":$trnxSeriesNo,
				    "pay_change": 0,
				    "booklet_id": 334,
				    "student": "2024-0112 Francis Noel B Maginangca ",
				    "section": "Grade 3 Emerald",
				    "account_id": "GRS80044",
				    "account_type": "student",
				    "esp": trnxSY,
				    "doc_type": "OR",
				    "pay_type": "CASH",
				    "transac_date": trnxDateFormat,
				    "pay_due": trnxAmount,
				    "pay_amount": trnxAmount,
				    "details": [
				        {
				            "id": "SBQPY",
				            "description": "Subsequent Payment",
				            "amount": trnxAmount,
				            "docType": "OR",
				            "isActive": true
				        }
				    ]
				}
		}
		$selfScope.$watchGroup(['CAD.ActiveOther.id','CAD.ActiveStudent.id'],function(entity){
			$scope.hasValidPayee = entity[0] ||  entity[1];
			if(!$scope.hasValidPayee){
				let asy = angular.copy(atomic.ActiveSY);
				$scope.ActiveSY = asy;
			}
		});
		$selfScope.$watch('CAD.PayeeType',function(){
			let asy = angular.copy(atomic.ActiveSY);
				$scope.ActiveSY = asy;
		});

		$selfScope.$on('PaymentError',function(evt,args){

			alert(args.message);
		});
	}]);

});