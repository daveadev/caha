"use strict";
define(['app','transact','api','atomic/bomb'],function(app,TRNX){
	const TRNX_LIST = TRNX.__LIST;
	const NEXT_SY = true;
	app.register.controller('CashierController',['$scope','$rootScope','$filter','api','Atomic',
	function($scope,$rootScope,$filter,api,atomic){
		const $selfScope =  $scope;
		$scope = this;

		$scope.init = function(){
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
			$scope.Props = ['description','amount'];

			$scope.PSHeaders = ['Due Date', 'Amount','Status'];
			$scope.PSProps = ['disp_date','disp_amount','status'];
			$scope.Paysched = [];
			$scope.StudFields = ['id','full_name','enroll_status','student_type','department_id','year_level_id'];
			$scope.TransacDetails=[];
			$scope.TotalAmount = 5000;
			$scope.SeriesNo = 'OR 12345';
			$scope.TransacDate = new Date();
		}
		
		$selfScope.$watchGroup(['CAC.ActiveStudent','CAC.ActiveSY'],function(entity){
			var stud = entity[0];
			var sy = entity[1];
			if(!stud||!sy) return;
			$selfScope.$broadcast('StudentSelected',{student:stud,sy:sy});
		});

		$selfScope.$on('UpdatePaysched',function(evt,args){
			$scope.Paysched = args.paysched;
		});
	}]);

	app.register.controller('CashierTransactionsController',['$scope','$rootScope','api','Atomic',
	function($scope,$rootScope,api,atomic){
		const $selfScope =  $scope;
		$scope = this;
		$scope.init = function(){
			TRNX.runDefault();
			$scope.TransacList = TRNX.getList();
			TRNX.link(api);
		};

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
				

				$scope.TransacList = TRNX.getList();
				var sched =  TRNX.getSched();
				$selfScope.$emit('UpdatePaysched',{paysched:sched});
				if(account.old_balance>0){
					TRNX.getOldAccount(sid,sy);
				}
				
			}
			if(loadNextSY)
				return TRNX.getAssessment(sid,sy).then(updateTrnx);
			
			TRNX.getAccount(sid,sy).then(updateTrnx);
				
			

		});
		
	}]);

});