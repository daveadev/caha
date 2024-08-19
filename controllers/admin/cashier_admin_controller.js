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
			$scope.Headers = [{class:'col-md-4',label:'Student'},'Date','Series No',{class:'col-md-2 text-right',label:'Amount'}];
			$scope.Props = ['search','transact_date','series_no','amount'];
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
			$scope.ActiveRecord = {};
			$scope.TransacDate = new Date();
			$scope.TotalDispAmount = TRNX.util.formatMoney($scope.TotalAmount);
		}

		$scope.saveInputs = function(){
			var trnxSeriesNo = $scope.SeriesNo;
			var trnxAmount = $scope.Amount;
			var trnxDate = $scope.TransactDate;
			var	trnxDateFormat = $filter('date')(new Date(trnxDate),DATE_FORMAT);
			var trnxSY = $scope.ActiveSY;
			var student = $scope.ActiveStudent;
			
			var trnxData= {
				    "series_no":trnxSeriesNo,
				    "pay_change": 0,
				    "booklet_id": 334,
				    "student": student.display_name,
				    "section": student.section,
				    "account_id": student.id,
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
				};
			let success = function(response){
				let data = response.data;
				$selfScope.$emit('PaymentSucess',{details:data});
			};
			let error = function(response){
				$selfScope.$emit('PaymentError',{message:response.message});


			};
			api.POST('new_payments',trnxData,success,error);
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

		$selfScope.$watch('CAD.Records',function(cad_records){
			if(!cad_records.length) return;
			let records = [];
			cad_records.forEach(function(record,i){
				let r = angular.copy(record);
				r.id = i+1;
				const nameDetails = extractNameDetails(r.student);
				r.search =  `${nameDetails.lastname} ${nameDetails.firstname}`
				records.push(r);
			});
			$scope.CleanRecords = records;
			$scope.StarBatch = true;
			$scope.BatchIndex=0;
			$scope.OverallProgress = `Batch process started...`;
			$timeout(function(){
				loadRecord($scope.BatchIndex);
			},1500);
		});

		$selfScope.$on('PaymentSucess',function(evt,args){
			console.log(args);
			$timeout(function(){
				loadRecord($scope.BatchIndex++);
			},500);
			
		});
		$selfScope.$on('PaymentError',function(evt,args){
			$scope.ProgressText = `${args.message}`;
			console.error(args.message);
			$timeout(function(){
				loadRecord($scope.BatchIndex++);
			},500);
		});

		function extractNameDetails(fullName) {
		    // Step 1: Extract the last name (everything before the semicolon)
		    const [lastNamePart, rest] = fullName.split(';').map(part => part.trim());

		    // Step 2: Extract the first word after the semicolon (the first name)
		    const [firstName] = rest.split(' ').map(part => part.trim());

		    // Step 3: Return the extracted components
		    return {
		        lastname: lastNamePart,
		        firstname: firstName,
		    };
		}

		function loadRecord(index){
			let itemCount = index+1;
			let recordLen = $scope.CleanRecords.length;
			$scope.OverallProgress = `Loading records  ${itemCount} of ${recordLen}...`
			let record = $scope.CleanRecords[index];
			$scope.ActiveStudent = null;
			$scope.TransactDate = new Date(record.transact_date);
			$scope.SeriesNo = record.series_no;
			$scope.Amount = parseFloat(record.amount);
			let filter = {
				keyword:record.search,
				fields:'last_name,first_name'
			};
			let success = function(response){
				let elem = document.querySelector('#searchEntity-1001');
				let data = response.data;
				if(data.length==1){
					$scope.ProgressText = `Tagging record of ${record.series_no}`
					$scope.ActiveStudent = data[0];
					$scope.ActiveRecord = record;
					$timeout(function(){
						angular.element(elem).val($scope.ActiveStudent.display_name);
						$scope.saveInputs();
					},150);
				}
			};
			let error = function(response){};
			api.GET('students/search',filter,success,error);
		}
	}]);

});