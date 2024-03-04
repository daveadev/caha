"use strict";
define(['app','transact','booklet','api','atomic/bomb'],function(app,TRNX,BKLT){
	const DATE_FORMAT = "yyyy-MM-dd";
	const TRNX_LIST = TRNX.__LIST;
	let NEXT_SY = false;
	app.register.controller('CashierController',['$scope','$rootScope','$filter','$timeout','api','aModal','Atomic',
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
			$scope.StudFields = ['id','full_name','enroll_status','student_type','department_id','year_level_id','section'];
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
		$scope.editTrnxDetails = function(){
			$scope.TrnxDtlMode = 'EDIT';
			$scope.allowUpdate = true;
			$scope.allowPay = false;
			$scope.EditTransacDetails = angular.copy($scope.TransacDetails);
		}
		$scope.clearTrnxDetails = function(){
			let asy = angular.copy(atomic.ActiveSY);
			$scope.ActiveSY = asy;
			$scope.TrnxDtlMode = 'RESET';
			$selfScope.$broadcast('ResetTransactions');
			
		}
		$scope.closeTrnxDetails = function(){
			$scope.TrnxDtlMode = 'VIEW';
			$scope.allowUpdate = false; 
		}
		$scope.updateTrnxDetails = function(){
			$scope.TrnxDtlMode = 'VIEW';
			$scope.allowPay = true;
			let trnxDetails = $scope.EditTransacDetails;
			$selfScope.$emit('UpdateTransacDetails',{details:trnxDetails,format:true});
			$selfScope.$broadcast('UpdateTransacList',{details:trnxDetails});
		}
		$scope.updateDetails = function(details){
			let hasZero = false;
			details.map(function(dtl){
				if(dtl.amount==0)
					hasZero=true;
			});
			if(hasZero) return;
			$scope.EditTransacDetails = details;
		}

		$selfScope.$watchGroup(['CAC.ActiveOther.id','CAC.ActiveStudent.id'],function(entity){
			$scope.hasValidPayee = entity[0] ||  entity[1];
			if(!$scope.hasValidPayee){
				let asy = angular.copy(atomic.ActiveSY);
				$scope.ActiveSY = asy;
			}
		});
		$selfScope.$watch('CAC.PayeeType',function(){
			let asy = angular.copy(atomic.ActiveSY);
				$scope.ActiveSY = asy;
		});
		$selfScope.$watchGroup(['CAC.ActiveOther','CAC.ActiveSY','CAC.ActiveOtherId'],function(entity){
			var othr = entity[0];
			var sy = entity[1];
			var oid = entity[2];
			if(!othr||!sy) return;
			if(oid && !othr.id){
				let name = othr.name || othr;
				othr = {id:oid,name:name};
				$scope.ActiveOther = othr;
				$scope.ActiveOtherId = null;
				othr.account_details = name;

			}
			if(!othr.id)
				resetTransactionUI();
				
			$selfScope.$broadcast('OtherSelected',{other:othr,sy:sy});
		});
		$selfScope.$watchGroup(['CAC.ActiveStudent','CAC.ActiveSY'],function(entity){
			var stud = entity[0];
			var sy = entity[1];
			if(!stud||!sy) return;
			if(!stud.id){
				resetTransactionUI();
			}
			let asy = atomic.ActiveSY;
			if(NEXT_SY && sy<asy+1 && stud.enroll_status=='NEW'){
				$scope.ActiveSY = asy+1; 
			}

			$selfScope.$broadcast('StudentSelected',{student:stud,sy:sy});
		});
		$selfScope.$watch('CAC.TotalAmount',function(amount){
			let isValid = $scope.hasValidPayee;
			if(!isValid) return;
			$scope.allowPay = isValid && $scope.ActiveSY && amount>0;
			$scope.allowClear = $scope.allowPay && $scope.TrnxDtlMode!=='EDIT';
			$scope.allowEdit = $scope.allowPay && $scope.TrnxDtlMode!=='EDIT';
		});

		$selfScope.$on('UpdatePaysched',function(evt,args){
			$scope.Paysched = args.paysched;
		});
		$selfScope.$on('UpdateTransacDetails',function(evt,args){
			let details = args.details;
			let formatAmount = args.format;
			let totalAmt = 0;
			$scope.TransacDetails = details;
			details.map(function(item,index){
				totalAmt+=item.amount;
				if(formatAmount)
					item.disp_amount = TRNX.util.formatMoney(item.amount);
			});
			$scope.TotalAmount = totalAmt;
			$scope.TotalDispAmount = TRNX.util.formatMoney(totalAmt);
		});

		$scope.openPaymentModal = function(){
			$selfScope.$broadcast('OpenPayModal',{total_amount:$scope.TotalAmount,total_amount_display:$scope.TotalDispAmount,details:$scope.TransacDetails});
		}

		$selfScope.$on('PaymentSucess',function(evt,args){
			let account_type = args.details.account_type;
			if(account_type=='others'){
				let othr = $scope.ActiveOther;
				let sy = $scope.ActiveSY;
				$selfScope.$broadcast('ResetTransactions');
				$timeout(function(){
					$selfScope.$broadcast('OtherSelected',{other:othr,sy:sy});
					$scope.ActiveTabIndex = 0;
				},1500);
			}else{

				let stud = $scope.ActiveStudent;
				let sy = $scope.ActiveSY;
				$selfScope.$broadcast('ResetTransactions');
				$timeout(function(){
					$selfScope.$broadcast('StudentSelected',{student:stud,sy:sy});
					$scope.ActiveTabIndex = 1;
				},1500);
			}
			$selfScope.$broadcast('ClosePayModal');
			$selfScope.$broadcast('PrintPaymentReceipt',{details:args.details});
		});
		$selfScope.$on('PaymentError',function(evt,args){

			alert(args.message);
		});
		$selfScope.$on('PrintPaymentReceipt',function(evt,args){
			$scope.PrintPaymentDetails = args.details;
			let docType = $scope.PrintPaymentDetails.doc_type;
			let payForm =  `Print${docType}Payment`;

			$timeout(function(){
				document.getElementById(payForm).submit();			
			},200);
		});

		function resetTransactionUI(){
			$selfScope.$broadcast('UpdatePaysched',{paysched:[]});
			$selfScope.$broadcast('ResetTransactions');
			
		}
	}]);

	app.register.controller('CashierTransactionsController',['$scope','$rootScope','$filter','$timeout','api','Atomic',
	function($scope,$rootScope,$filter,$timeout,api,atomic){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			TRNX.runDefault();
			$scope.TransacList = TRNX.getList();
			TRNX.link(api);
			$scope.ActiveTrnx = {};
		};

		$scope.addTrnx = function(id){
			$scope.ActiveTrnx = {};
			$scope.ActiveTrnx[id]=!$scope.ActiveTrnx[id];
			updateTrnxUI();
			let activeTrnx = $filter('filter')($scope.TransacList,{isActive:true});
			$selfScope.$emit('UpdateTransacDetails',{details:activeTrnx});
		}
		$selfScope.$on('ResetTransactions',function(evt,args){
			$scope.ActiveTrnx={};
			updateTrnxUI();
			$scope.TransacList=TRNX.getList();
			$selfScope.$emit('UpdateTransacDetails',{details:[]});
			$scope.PSType = null;
			$rootScope.__PSType =null;
		});

		$selfScope.$on('UpdateTransacList',function(evt,args){
			let details = args.details;
			details.map(function(dtl){
				$scope.TransacList.map(function(trl,index){
					if(dtl.id===trl.id){
						trl.amount = dtl.amount;
						trl.disp_amount = dtl.disp_amount;
						$scope.TransacList[index]=trl;
					}
				});
			});
			
		});
		function updateTrnxUI(){
			$scope.TransacList.map(function(item,index){
				$scope.TransacList[index].isActive = !!$scope.ActiveTrnx[item.id];
			});
		}
		$selfScope.$on('OtherSelected',function(evt,args){
			$scope.ActiveTrnx = {};
			let OTH = args.other;
			let oid = OTH.id;
			let sy = args.sy;
			if(!oid) return;
			$scope.loadingTrnx = true;
			TRNX.runDefault();
			$selfScope.$emit('UpdatePaysched',{paysched:[]});
				
			function updateTrnx(response){
				var account =  response.data.data[0];
				console.log(response);
				$scope.TransacList = angular.copy(TRNX.getList());
				$scope.loadingTrnx = false;
			}
			TRNX.getAccount(oid,sy).then(updateTrnx);

		});
		$selfScope.$on('StudentSelected',function(evt,args){
			$scope.ActiveTrnx = {};
			var STU =  args.student;
			var sy = args.sy;
			var sid = STU.id;
			var type =  STU.enroll_status;
			var asy = atomic.ActiveSY;
			if(!sid) return;
			$scope.loadingTrnx = true;
			TRNX.runDefault();
			$scope.TransacList = angular.copy(TRNX.getList());

			
			$selfScope.$emit('UpdatePaysched',{paysched:[]});

			if(!sid) return;
			
			var loadNextSY = NEXT_SY && sy> asy;
			var hasOldAccount = false;

			function updateTrnx(response){
				
				var account =  response.data.data[0];
				var type =  account.hasOwnProperty('Paysched')?'regular':'old';
				var sched =  TRNX.getSched(type);
				$selfScope.$emit('UpdatePaysched',{paysched:sched});
				if(type=='old' &&sched.length>1)
					hasOldAccount = true;
				
				let specialPay = [];
				$scope.TransacList = angular.copy(TRNX.getList());
				$scope.TransacList.map(function(trl){
					let ispayment = /^(EXTPY|SBQPY)$/.test(trl.id) && trl.amount>0;
					if (ispayment)
						specialPay.push(trl.id);
				});
				$scope.PSType = type;
				$rootScope.__PSType = type;
				$rootScope.hasMultiplePS =  specialPay.length>1 || hasOldAccount;
				console.log(hasOldAccount,specialPay);
				$selfScope.$emit('DisplaySOA');
				
			}
			
			function loadCurrentAccount(){
				TRNX.getOldAccount(sid,sy).then(updateTrnx).finally(function(){
					$timeout(function(){
						TRNX.getAccount(sid,sy).then(updateTrnx);
						$scope.loadingTrnx = false;	
						$selfScope.$emit('DisplaySOA');
					},300);
					
				});

			}

			if(loadNextSY){
				TRNX.getAssessment(sid,sy).then(updateTrnx).finally(function(){
					$scope.loadingTrnx = false;	
					TRNX.updateAmount('INRES','set',3000);
					TRNX.updateDisplay('INRES','show');
					$scope.TransacList = angular.copy(TRNX.getList());
					return loadCurrentAccount(sid,sy);
				});
			}

			return loadCurrentAccount(sid,sy);

			
			
		});
		$selfScope.$watch('CAC.PSType',function(type){
			var sched =  TRNX.getSched(type);
			$selfScope.$emit('UpdatePaysched',{paysched:sched});
			$selfScope.$emit('DisplaySOA');
			$rootScope.__PSType =type;
		});
		$selfScope.$on('DisplaySOA',function(){
			$timeout(function(){
				document.getElementById('PrintPPSoa2').submit();
			},200);
		});

	}]);
	

	app.register.controller('CashierModalController',['$scope','$rootScope','$filter','$timeout','api','aModal',
	function($scope,$rootScope,$filter,$timeout,api,aModal){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			BKLT.link(api);
			$scope.PayObj = {};
			$scope.DocTypes = [
					{id:"OR", name:"Official Receipt"},
					{id:"A2O", name:"A2O"},
					{id:"AR", name:"AR"},
				];
			$scope.PayTypes = [
					{id:"CASH",name:"Cash"},
					{id:"CHCK",name:"Check"}
					//{id:"CARD",name:"Card"}
				];

			
		}
		$selfScope.$on('OpenPayModal',function(evt,args){
			aModal.open('CashierPaymentModal');

			let defaultDocType =  args.details[0].docType || 'A2O';
			$scope.PayObj.series_no = null;
			$scope.PayObj.doc_type = defaultDocType;
			$scope.PayObj.pay_type = 'CASH';
			$scope.PayObj.transac_date = new Date();
			$scope.PayObj.transac_date_display = $filter('date')(new Date(),'dd MMM yyyy');
			$scope.PayObj.pay_due = args.total_amount
			$scope.PayObj.pay_amount = args.total_amount;
			$scope.PayObj.pay_display =args.total_amount_display;
			$scope.PayObj.details =  args.details;
			$scope.isPayObjValid = false;
		});
		$selfScope.$on('ClosePayModal',function(evt,args){
			$scope.PayObj.series_no =null;
			$scope.PayObj.doc_type =null;
			$scope.PayObj.pay_due =null;
			$scope.PayObj.pay_amount =null;
			$scope.PayObj.pay_display =null;
			$scope.PayObj.pay_details =null;
			$scope.PayObj.pay_date =null;
			$scope.PayObj.transac_date =null;
			$scope.PayObj.transac_date_display =null;
			$scope.PayObj.details =null;
			aModal.close('CashierPaymentModal');
		});
		$selfScope.$watch('CMC.PayObj.doc_type',loadBooklet);
		$selfScope.$watchGroup(['CMC.PayObj.series_no','CMC.isCheckingSeries','CMC.PayObj.pay_type','CMC.PayObj.pay_details','CMC.PayObj.pay_date'],function(values){
			$scope.isPayObjValid  = $scope.PayObj.series_no!=null && !$scope.isCheckingSeries;
			if(values[2]=='CHCK'){
				let checkDetails = $scope.PayObj.pay_details && $scope.PayObj.pay_date;
				$scope.isPayObjValid  =  $scope.isPayObjValid  && checkDetails
			}
		});
		$selfScope.$on('OtherSelected',function(evt,args){
			$scope.PayObj.other = args.other.account_details;
			$scope.PayObj.account_id = args.other.id;
			$scope.PayObj.account_type = 'others';
			$scope.PayObj.esp = args.sy;
		});
		$selfScope.$on('StudentSelected',function(evt,args){
			$scope.PayObj.student = args.student.name;
			$scope.PayObj.section = args.student.section;
			$scope.PayObj.account_id = args.student.id;
			$scope.PayObj.account_type = 'student';
			$scope.PayObj.esp = args.sy;
			
		});
		$selfScope.$watch('CMC.PayObj.pay_amount',function(amt){
			let pay_due = $scope.PayObj.pay_due;
			let pay_change = amt - pay_due;
			$scope.PayObj.pay_change = pay_change; 
		});
		$scope.closeModal = function(){
			$selfScope.$emit('ClosePayModal');
		}
		
		$scope.confirmPayment = function(){
			let payment = angular.copy($scope.PayObj);
				payment.transac_date =  $filter('date')(new Date(payment.transac_date),DATE_FORMAT);;
			let success = function(response){
				let data = response.data;
				$selfScope.$emit('PaymentSucess',{details:data});
			};
			let error = function(response){
				$selfScope.$emit('PaymentError',{message:response.message});

			};
			api.POST('new_payments',payment,success,error);
		}

		function loadBooklet(){
			let doc_type = $scope.PayObj.doc_type;
			$scope.PayObj.series_no = 'Checking...';
			$scope.isCheckingSeries = true;
			$scope.isPayObjValid = false;
			$timeout(function(){
				BKLT.requestBooklets(doc_type).then(setDefaults,errDefaults);
			},350);
		}
		function setDefaults(){
			
			$scope.Booklets = BKLT.getBooklets();
			let bklt_id = $scope.Booklets[0].id;
			let active_BL = BKLT.setActiveBL(bklt_id);
			$scope.PayObj.booklet_id = bklt_id;
			$scope.PayObj.series_no = active_BL.series_no;
			$timeout(function(){
				$scope.isCheckingSeries = false;	
			},700);
		}
		function errDefaults(){
			$scope.isCheckingSeries = false;
			$scope.PayObj.series_no = null;
		}
	
		
	}]);


});