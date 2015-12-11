"use strict";
define(['app','api'], function (app) {
    app.register.controller('BookletController',['$scope','$rootScope','$uibModal','api', function ($scope,$rootScope,$uibModal,api) {
		console.log($uibModal);
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Booklets';
			//Initialize components
			function getBooklets(data){
				$scope.DataLoading=true;
				api.GET('booklets',data,function success(response){
					console.log(response.data);
					$scope.Booklets=response.data;
					$scope.NextPage=response.meta.next;
					$scope.PrevPage=response.meta.prev;
					$scope.TotalItems=response.meta.count;
					$scope.LastItem=response.meta.page*response.meta.limit;
					$scope.FirstItem=$scope.LastItem-(response.meta.limit-1);
					if($scope.LastItem>$scope.TotalItems){
						$scope.LastItem=$scope.TotalItems;
					};
					$scope.DataLoading = false;							
				});
			}
			$scope.initBooklets=function(){
				$scope.Booklets=[];
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.ActivePage = 1;
				$scope.NextPage=null;
				$scope.PrevPage=null;
				$scope.DataLoading = false;
				getBooklets({page:$scope.ActivePage});
			};
			$scope.initBooklets();
			$scope.navigatePage=function(page){
				$scope.ActivePage=page;
				getBooklets({page:$scope.ActivePage});
			};
			//Open Booklet Information
			$scope.openBooklet=function(booklet){
				$scope.Booklet = booklet;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
			};
			//Remove Booklet Information
			$scope.removeBookletInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.Booklet = null;
			};
			//Filter Booklet
			$scope.filterBooklet=function(booklet){
				var searchBox = $scope.searchBooklet;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(booklet.series_start) || keyword.test(booklet.series_end);
				return !searchBox || test;
			};
			$scope.confirmSearch = function(){
				getBooklets({page:$scope.ActivePage,keyword:$scope.searchBooklet,fields:['series_end']});
			}
			//Filter search box
			$scope.clearSearch = function(){
				$scope.searchBooklet = null;
			};
			$scope.deleteBooklet = function(id){
				var data = {id:id};
				api.DELETE('booklets',data,function(response){
					$scope.removeBookletInfo();
					getBooklets({page:$scope.ActivePage});
				});
			};
			$scope.deactivateBooklet=function(id){
				var data = {id:id,status:'inactive'};
				api.POST('booklets',data,function success(response){
					$scope.removeBookletInfo();
					getBooklets({page:$scope.ActivePage});
				});
			};
			$scope.activateBooklet=function(id){
				var data = {id:id,status:'active'};
				api.POST('booklets',data,function success(response){
					$scope.removeBookletInfo();
					getBooklets({page:$scope.ActivePage});
				});
			};
			//Opening the modal
			$scope.openModal=function(){
				var modalInstance = $uibModal.open({
					animation: true,
					templateUrl: 'myModalContent.html',
					controller: 'ModalInstanceController',
				});
				modalInstance.result.then(function (selectedItem) {
				  $scope.selected = selectedItem;
				}, function (source) {
					//Re-initialize booklets when confirmed
					if(source==='confirm')
						$scope.initBooklets();
				});
			};
		};
    }]);
	app.register.controller('ModalInstanceController',['$scope','$rootScope','$uibModalInstance','api', function ($scope,$rootScope, $uibModalInstance, api){
		//Get the data entered and push it to booklets.js
		$scope.confirmBooklet = function(){
			 $scope.Booklets={
						  series_start: $scope.seriesStart,
						  series_end: $scope.seriesEnd,
						  series_counter: $scope.seriesCounter,
						  status: "active",
						  cashier: $scope.cashier
						 };
			api.POST('booklets',$scope.Booklets,function success(response){
				$uibModalInstance.dismiss('confirm');
			});
		};
		//This is for ng-blur
		$scope.setSeriesCounter=function(seriesStart){
			$scope.seriesCounter=$scope.seriesStart;
		};
		//Close modal
		$scope.cancelBooklet = function(){
			$uibModalInstance.dismiss('cancel');
		};
	}]);
	
});


