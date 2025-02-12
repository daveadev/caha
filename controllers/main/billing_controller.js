"use strict";
define(['app','billings','api','atomic/bomb','caha/api'],function(app,billings){
	const DATE_FORMAT = "yyyy-MM-dd";
	const BILL_MONTHS = billings.generateBillingMonths('BILL');
	const LAST_BILL_MO = BILL_MONTHS.length-1;
	let NEXT_SY = false;
	app.register.controller('BillingController',['$scope','$sce','$rootScope','$filter','$timeout','api','aModal','Atomic','cahaApiService',
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

			$scope.BillHeaders = [{class:'col-md-1',label:'Stud No.'},{class:'col-md-3',label:'Name'},'Section','Billing No.',{class:'col-md-2 amount',label:'Due Amount'},{class:'col-md-2 amount' ,label:'Paid Amount'},'Online','Status'];
			$scope.BillProps = ['sno','student','section','billing_no','due_amount_disp','paid_amount_disp','has_online_pay','status'];
			$scope.BillData=[];
			$scope.SearchFields =['student', 'sno'];
			$scope.BillObj =null;
			$scope.BillObj =null;

			$scope.BillingMonths =BILL_MONTHS;
			$scope.BillMonth = BILL_MONTHS[LAST_BILL_MO].id;
			$scope.BillStatuses = [
				{id:'UNPAID',name:"UNPAID"},
				{id:'PARTIAL',name:"PARTIAL"},
				{id:'PAID',name:"PAID"}
				];

			$scope.OLPHeaders =[{class:'col-md-2',label:'Ref No.'},'Source',{class:'col-md-2',label:'Amount'},{class:'col-md-3',label:'SI No.'},{class:'col-md-3',label:'Status'}];
			$scope.OLPProps =['refno','pay_channel','amount','ornum','status'];
			$scope.OLPStatuses =[
					{id:'Pending',name:"Pending"},
					{id:'Verified',name:"Verified"},
					{id:'Cancelled',name:"Cancelled"}
				];
			$scope.OLPRecords =[];
			if(!$scope.YearLevels && atomic.YearLevels)
				atomic.fuse();
			
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
				let sno = $scope.ActiveBillObj.sno;
				$scope.BillURL = 'api/reports/billings/'+billNo;
				aModal.open('BillingModal');
				loadPayments(sno);
			},500);
			
			
		}
		$scope.closeModal = function(){
			$scope.BillObj =null;
			aModal.close('BillingModal');
		}
		$selfScope.$watch('BLC.BillMonth',function(bMonth){
			if(!bMonth) return;
			$scope.FilteredBillData=[];
			$scope.getBillDetails();
		});
		$scope.getBillDetails = function(){
			let dueDate = $scope.BillMonth;
			$scope.BillData=[];
			let data = {'limit':'less',due_date:dueDate};
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
					let status =  billData[i].status;
					billData[i].class = status=='PAID'?'': status=='PARTIAL'?'warning':'danger';

				}
				$scope.BillData = billData;
				$scope.filterBill();
				$scope.getAllPayments();
			};
			let error = function(response){
				alert(response.meta.message);

			};
			api.GET('billings',data,success,error);
		}

		$scope.getAllPayments = function(){
			let success = function(response){
				let onlinePayments = response.data;
				let hasOnlinePay = {};
				for(var index in onlinePayments){
					let opObj = onlinePayments[index];
					hasOnlinePay[opObj.sno] = true;
				}
				for(var index in $scope.BillData){
					let bObj = $scope.BillData[index];
					let sno = bObj.sno;
					$scope.BillData[index].has_online_pay = hasOnlinePay[sno]?'Yes':'-';
				}

			}
			let error = function(response){
				alert(response.meta.message);

			};
			cahaapi.getAllPayments('Pending',success,error);
		}

		$scope.updateSOA = function(){
			$scope.isUpdating = true;
			//pdfUrl, fileName, uploadUrl
			let pdfURL = $scope.BillURL;
			let billObj = $scope.ActiveBillObj;
			let sno = billObj.sno;
			let billMonth = billObj.bill_month;
			let fileName = `${sno}-${billMonth}-${billObj.full_name}.pdf` ;

			let successUpload = function(response){
				if(response.data.status ==200){
					let data = {
						bill_id:billObj.bill_id,
						paid_amount:billObj.paid_amount,
						status:billObj.status
					};
					let successUpdate = function(response){
						$scope.isUpdating = false;
						console.log(response.data);
					}
					let errorUpdate = function(response){
						$scope.isUpdating = false;
						console.log(response.data);
					}
					cahaapi.updateSOA(sno, billMonth, data, successUpdate, errorUpdate);
				}
				
			};
			let errorUpload = function(response){
				console.log(response.data);
			};

			cahaapi.uploadSOA(pdfURL,fileName, successUpload, errorUpload);
		}

		$scope.updateInfo =function(){
			$scope.isUpdating = true;
			let billObj = $scope.ActiveBillObj;
			let sno = billObj.sno;
			let data = {
				id:billObj.account_id,
				last_name:billObj.last_name,
				first_name:billObj.first_name,
				middle_name:billObj.middle_name,
				mobile:billObj.mobile,
				email:billObj.email
			}
			let successUpdate = function(response){
				$scope.isUpdating = false;
				console.log(response.data);
			}
			let errorUpdate = function(response){
				$scope.isUpdating = false;
				console.log(response.data);
			}
			cahaapi.updateInfo(sno, data,successUpdate,errorUpdate);
		}

		$scope.updatePayment =function(){
			$scope.isUpdating = true;
			let olpObj = $scope.ActiveOLPObj;
			let sno = olpObj.sno;
			let token = olpObj.token;
			let data = {
				status:olpObj.status,
				ornum:olpObj.ornum,
				paid_amount:olpObj.amount
			}
			let successUpdate = function(response){
				$scope.isUpdating = false;
				console.log(response.data);
			}
			let errorUpdate = function(response){
				$scope.isUpdating = false;
				console.log(response.data);
			}
			cahaapi.updatePayment(sno, token,data,successUpdate,errorUpdate);
		}
		function loadPayments(sno){
			let status = 'ALL';
			$scope.OLPRecords =[];
			$scope.preload =true;
			let successGet =function(response){
				$scope.preload =false;
				$scope.OLPRecords = response.data;
			};
			let errorGet =function(response){};
			cahaapi.getPayments(sno,status,successGet, errorGet);
		}
		$selfScope.$watch('BLC.ActiveOLPObj',function(obj){
			$scope.ProofURL='';
			if(!obj) return;
			$scope.ProofURL =  $sce.trustAsResourceUrl(`https://rainbow.marqa.one/proof-api/view/${obj.token}`);
		});
	}]);

});