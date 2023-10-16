"use strict";
define(['app','transact','booklet','api','atomic/bomb'],function(app,TRNX,BKLT){
	const DATE_FORMAT = "yyyy-MM-dd";
	const TRNX_LIST = TRNX.__LIST;
	const NEXT_SY = false;
	app.register.controller('CashierController',['$scope','$rootScope','$filter','$timeout','api','aModal','Atomic',
	function($scope,$rootScope,$filter,$timeout,api,aModal,atomic){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
			$rootScope.$watch('_APP',function(){
				$scope.ActiveSY = $rootScope._APP.ACTIVE_SY;	
			});
			
			atomic.ready(function(){
				var sys = atomic.SchoolYears;
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
			$scope.Inputs = [{field:'description',disabled:true},{field:'amount',type:'number'}];

			$scope.PSHeaders = ['Due Date', 'Due Amount','Status'];
			$scope.PSProps = ['disp_date','disp_amount','status'];
			$scope.Paysched = [];
			$scope.StudFields = ['id','full_name','enroll_status','student_type','department_id','year_level_id','section'];
			$scope.TransacDetails=[];
			$scope.TotalAmount = 0;
			$scope.SeriesNo = '';
			$scope.TransacDate = new Date();
		}
		$scope.editTrnxDetails = function(){
			$scope.TrnxDtlMode = 'EDIT';
			$scope.allowUpdate = true;
			$scope.allowPay = false;
			$scope.EditTransacDetails = angular.copy($scope.TransacDetails);
		}
		$scope.clearTrnxDetails = function(){
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
		$selfScope.$watchGroup(['CAC.ActiveStudent','CAC.ActiveSY'],function(entity){
			var stud = entity[0];
			var sy = entity[1];
			if(!stud||!sy) return;
			if(!stud.id){
				$selfScope.$broadcast('UpdatePaysched',{paysched:[]});
				$selfScope.$broadcast('ResetTransactions');
				
			}
			$selfScope.$broadcast('StudentSelected',{student:stud,sy:sy});
		});
		$selfScope.$watch('CAC.TotalAmount',function(amount){
			$scope.allowPay = $scope.ActiveStudent.id && $scope.ActiveSY && amount>0;
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
			$selfScope.$broadcast('OpenPayModal',{total_amount:$scope.TotalAmount,details:$scope.TransacDetails});
		}

		$selfScope.$on('PaymentSucess',function(evt,args){
			let stud = $scope.ActiveStudent;
			let sy = $scope.ActiveSY;
			$selfScope.$broadcast('ResetTransactions');
			$selfScope.$broadcast('StudentSelected',{student:stud,sy:sy});
			$scope.ActiveTabIndex = 1;
			aModal.close('CashierPaymentModal');
			$selfScope.$broadcast('PrintPaymentReceipt',{details:args.details});
		});
		$selfScope.$on('PaymentError',function(evt,args){

			alert(args.message);
		});
		$selfScope.$on('PrintPaymentReceipt',function(evt,args){
			$scope.PrintPaymentDetails = args.details;
			$timeout(function(){
				document.getElementById('PrintPayment').submit();			
			},200);
		});
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
		$selfScope.$on('StudentSelected',function(evt,args){
			var STU =  args.student;
			var sy = args.sy;
			var sid = STU.id;
			var type =  STU.enroll_status;
			var asy = atomic.ActiveSY;


			TRNX.runDefault();
			$selfScope.$emit('UpdatePaysched',{paysched:[]});

			if(!sid) return;
			
			var loadNextSY = NEXT_SY && sy> asy;
			function updateTrnx(response){
				var account =  response.data.data[0];
				

				$scope.TransacList = angular.copy(TRNX.getList());
				var sched =  TRNX.getSched();
				$selfScope.$emit('UpdatePaysched',{paysched:sched});
				
			}
			$timeout(function(){
				document.getElementById('PrintPPSoa2').submit();
			},200);
			if(loadNextSY)
				return TRNX.getAssessment(sid,sy).then(updateTrnx);
			
			TRNX.getOldAccount(sid,sy).then(updateTrnx);
				
		});
	}]);
	

	app.register.controller('CashierModalController',['$scope','$rootScope','$filter','api','aModal',
	function($scope,$rootScope,$filter,api,aModal){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			BKLT.link(api);
			$scope.PayObj = {};
			$scope.DocTypes = [
					//{id:"OR", name:"Official Receipt"},
					{id:"AR", name:"AR"},
				];
			$scope.PayTypes = [
					{id:"CASH",name:"Cash"},
					//{id:"CHCK",name:"Check"},
					//{id:"CARD",name:"Card"}
				];

			
		}
		$selfScope.$on('OpenPayModal',function(evt,args){
			aModal.open('CashierPaymentModal');
			$scope.PayObj.series_no = 'AR 1230';
			$scope.PayObj.doc_type = 'AR';
			$scope.PayObj.pay_type = 'CASH';
			$scope.PayObj.transac_date = new Date();
			$scope.PayObj.pay_due = args.total_amount
			$scope.PayObj.pay_amount = args.total_amount;
			$scope.PayObj.details =  args.details;
			loadBooklet();
			
		});
		$selfScope.$on('StudentSelected',function(evt,args){
			$scope.PayObj.student = args.student.name;
			$scope.PayObj.section = args.student.section;
			$scope.PayObj.account_id = args.student.id;
			$scope.PayObj.esp = args.sy;
			
		});
		$selfScope.$watch('CMC.PayObj.pay_amount',function(amt){
			let pay_due = $scope.PayObj.pay_due;
			let pay_change = amt - pay_due;
			$scope.PayObj.pay_change = pay_change; 
		});
		$scope.closeModal = function(){
			aModal.close('CashierPaymentModal');
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
			BKLT.requestBooklets(doc_type).then(setDefaults);
			
		}
		function setDefaults(){
			$scope.Booklets = BKLT.getBooklets();
			let bklt_id = $scope.Booklets[0].id;
			let active_BL = BKLT.setActiveBL(bklt_id);
			$scope.PayObj.booklet_id = bklt_id;
			$scope.PayObj.series_no = active_BL.series_no;
		}
	
		
	}]);


});