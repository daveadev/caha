"use strict";
define(['app','api'], function (app) {
    const COOKIE_EXPIRY = {expires:new Date(new Date().getTime()+(app.settings.COOKIE_EXPIRY))};
	app.register.controller('RegisterController',['$scope','$rootScope','$window','$cookies','api', function ($scope,$rootScope,$window,$cookies,api) {
		$scope.Register = {};
		$scope.cancel = function(){
			$scope.Register = {};
		}
		$scope.register = function(){
			var data =  $scope.Register;
				$scope.Registering = true;
			api.POST('register',data,function(response){
				$scope.Registering = false;
				if(response.data){
					$cookies.put('__USER',JSON.stringify(response.data),COOKIE_EXPIRY);
					$window.location.href="#/";
				}
			});
		}
		
	}]);
	
	
	app.register.controller('LoginController',['$scope','$rootScope','$window','$cookies','api','localStorageService', '$interval',function ($scope,$rootScope,$window,$cookies,api,$locstor,$interval) {
		var isModuleListRequested;
		$rootScope.__SHOW_REG = false;
        $rootScope._APP_CopyRight =  document.querySelector('meta[name="copyright"]').getAttribute('content');
        $rootScope._APP_VersionNo =  document.querySelector('meta[name="version"]').getAttribute('content');
        $rootScope._APP_Name =  document.querySelector('meta[name="application-name"]').getAttribute('content');
        $rootScope._APP_Alias =  document.querySelector('meta[name="application-alias"]').getAttribute('content');
		
		if($window.location.hash=='#/logout'){
			$rootScope.__SIDEBAR_OPEN = false;
			$rootScope.__USER=null;
			$rootScope.RecentModules = [];
			$rootScope.ShowRecentModules =false;
			$rootScope.ActiveMenu =null;
			$cookies.remove('__USER');
			$cookies.remove('__MENUS');
			$locstor.remove('__SIDEBAR_MENUS');
			$rootScope.allowedItems = [];
			$rootScope.__SIDEBAR_MENUS = [];
			//$locstor.remove('__RECENT_MENUS');
			$window.location.href="#/login";
			api.POST('logout',function success(response){
					$rootScope.$emit('UserLoggedOut');
			});
		}
		if($rootScope.__USER){
			$window.location.href="#/";
		}
		$scope.cancel = function(){
			$scope.loginMessage = null;
			$scope.User = {};
		}
		$scope.cancel();
		$scope.login = function(){
			var data = $scope.User;
			$scope.LoggingIn = true;
			$scope.loginMessage =null;
			isModuleListRequested = false;
			api.POST('login',data,function(response){
				$scope.LoggingIn = false;
				$rootScope.RecentModules = [];
				$rootScope.ShowRecentModules =false;
				$rootScope.ActiveMenu =null;
				if(response.data.user){
					$rootScope.__USER = response.data;
					$cookies.put('__USER',JSON.stringify(response.data),COOKIE_EXPIRY);
					$rootScope.__LOGGEDIN  = true;
					$rootScope.$emit('UserLoggedIn');
					if(response.data.user.password_changed&&0){
						alert("Password needs to be changed.");
						$window.location.href="#/faculty/account_info";
					}else{
						$window.location.href="#/";	
					}
					$rootScope.$emit('LoadRecents');
					$rootScope.__SESS_START();
				}else{
					$scope.loginMessage = response.meta.message;
					$scope.User = '';
				}
			});
		}
		
		$rootScope.$on('UserLoggedIn',function(evt){
			if(!isModuleListRequested)
				requestModulesList('userLoggedIn');
			isModuleListRequested=true;
			
		});
		
		$rootScope.$on('$routeChangeStart', function (scope, next, current) {
			if($rootScope.__LOGGEDIN)
				if(!$rootScope.__SIDEBAR_MENUS)
					readModuleListCache();
				else if(!isModuleListRequested)
					requestModulesList('routeStart');
			isModuleListRequested=true;
		});
		
		function requestModulesList(source){
			console.log(source,isModuleListRequested);
			if(isModuleListRequested) return;
			api.GET('modules',{limit:'less'},function(response){
				var modules = response.data;
				var menus = [];
				var lastIndex=-1;
				for(var i in modules){
					var mod =  modules[i];
					if(!$rootScope.__USER) break;
					var granted = $rootScope.__USER.user.access.indexOf(mod.id)!==-1  ;

					if(!mod.is_child){
						//Menu
						if(mod.is_parent)
							mod.children=[];
						mod.is_granted = granted;
						if(granted|| mod.is_parent) menus.push(mod);
						lastIndex++;
					}else{
						//Submenu
						if(granted){
							menus[lastIndex].children.push(mod);
						}
							
							
					}
				}
				$locstor.set('__SIDEBAR_MENUS',JSON.stringify(menus));
				readModuleListCache();
			});
			
			
		}
		function readModuleListCache(){
				var sidebar = $locstor.get('__SIDEBAR_MENUS');
				var cache = JSON.parse(sidebar);
				$rootScope.__SIDEBAR_MENUS =  cache;
				var allowedItems =['account'];
				cache.map(function(menu){
					menu.children.map(function(child){
						allowedItems.push(child.link);
					})
				});
				$rootScope.allowedItems =  allowedItems;
				
		}
	}]);
});
