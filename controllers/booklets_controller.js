"use strict";
define(['app','api','atomic/bomb'], function (app) {
    app.register.controller('BookletController',['$scope','$rootScope','$uibModal','api','$filter','aModal', 
	function ($scope,$rootScope,$uibModal,api,$filter,aModal) {
		console.log($uibModal);
		$scope.list=function(){
			$rootScope.__MODULE_NAME = 'Booklets';
			
			//Initialize components
			function getBooklets(data){
				$scope.DataLoading=true;
				//Data contains the filter and pagination
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
				$scope.BookDetails = [];
				$scope.book = {};
				getCashiers();
				$scope.Headers = [{'label':'Booklet No.',class:'col-md-3'},{'label':'Doc Type',class:'col-md-2'},'Start','End', 'Counter'];
				$scope.Props = ['booklet_number','doctype','series_start','series_end', 'series_counter'];
				$scope.TranxHeaders = ['Date','Ref no','Amount'];
				$scope.TranxProps = ['transac_date','ref_no','amount'];
				$scope.Options = [{id:'OR',name:'OR'},{id:'AR',name:'AR'}];
				$scope.inputs = [{field:'booklet_number'},{field:'doctype',options:$scope.Options},{field:'series_start',type:'number'},{field:'series_end',type:'number'},{field:'series_counter',type:'number'}];
				
			};
			$scope.initBooklets();
			$scope.navigatePage=function(page){
				$scope.ActivePage=page;
				getBooklets({page:$scope.ActivePage});
			};
			
			//Open Booklet Information
			$scope.SetActiveBook=function(booklet){
				$scope.Transactions = '';
				$scope.ActiveBook = booklet;
				$scope.hasInfo = true;
				$scope.hasNoInfo = false;
				getBookletTransactions(1);
			};
			
			//Remove Booklet Information
			$scope.removeBookletInfo=function(){
				$scope.hasInfo = false;
				$scope.hasNoInfo = true;
				$scope.ActiveBook = null;
			};
			
			//Filter Booklet
			$scope.filterBooklet=function(booklet){
				var searchBox = $scope.searchBooklet;
				var keyword = new RegExp(searchBox,'i');	
				var test = keyword.test(booklet.series_start) || keyword.test(booklet.series_end);
				return !searchBox || test;
			};
			
			$scope.confirmSearch = function(){
				getBooklets({page:$scope.ActivePage,keyword:$scope.searchBooklet,fields:['series_start','series_end']});
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
				aModal.open("AddBooklet");
			};
			
			$scope.Cancel = function(){
				aModal.close("AddBooklet");
			};
			
			/* $scope.addBooklet = function(book){
				
				if(book.series_start>=book.series_end){
					alert('Booklet series start is greater than series end! Please re-enter'); 
					$scope.book = {};
					return;
				}
				if(!book.emp)
					book['Cashier'] = 'Unassigned';
				else
					book.cashier = book.emp.employee_name;
				book.series_counter = book.series_start;
				$scope.BookDetails.push(book);
				$scope.book = {};
				$scope.ctr = '';
			} */
			
			$scope.SaveBooklet = function(){
				
				var data = {Booklet:$scope.book};
				if($scope.Cashier)
					data.Cashier = $scope.Cashier;
				console.log(data);
				api.POST('booklets', data, function success(response){
					$scope.initBooklets();
					aModal.close("AddBooklet");
				}, function error(response){
					
				});
			}
			
			$scope.gotoPage = function(page){
				getBookletTransactions(page);
			}
			
			$scope.applyEdit =  function(items){
				if(items.length){
					$scope.book = items;
				}
			}
			
			$scope.assign = function(cashier){
				$scope.Cashier = cashier;
			}
			
			function getBookletTransactions(page){
				var data = {
					booklet_id:$scope.ActiveBook.id,
					page:page
				};
				api.GET('transactions',data, function success(response){
					$scope.Transactions = response.data;
					$scope.Meta = response.meta;
				}, function error(response){
					$scope.NoTrnx = 1;
				});
			}
			
			function getCashiers(){
				var data = {
					
				}
				api.GET('cashiers', data, function success(response){
					$scope.Cashiers = response.data;
				});
			}
		};
    }]);
	
	
});


