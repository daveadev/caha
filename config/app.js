"use strict";
define(['root','directives','settings','angularAMD','angular-route', 'angular-cookies','angular-local-storage','angular-chart','custom-window', 'ui-bootstrap','ui.tree'], 
function (root,directives,settings,angularAMD) {
    var app = angular.module("mainModule", 
        ['ngRoute', 'ngCookies', 'LocalStorageModule','chart.js','ui.bootstrap','ui.tree']);
	var mainRouter = angularAMD.route({
        templateUrl: function (rp) { 
			if(!rp.action)  rp.action ='index';
			return settings.VIEWS_DIRECTORY+'/' + rp.controller + '/' + rp.action +'.'+settings.VIEW_EXTENSION;
		},
        resolve: {
        load: ['$q', '$rootScope', '$location', 
            function ($q, $rootScope, $location) {
                var path = $location.path();
                var parsePath = path.split("/");
				var controllerName = parsePath[1];
                var loadController =  settings.CTRLS_DIRECTORY+"/"  + 
                                      controllerName + "_controller";
                var deferred = $q.defer();
                require([loadController], function () {
                    $rootScope.$apply(function () {
                        deferred.resolve();
                        });
            });
            return deferred.promise;
            }]
            }
        });
    app.config(['$routeProvider', function ($routeProvider) {
   
    $routeProvider

    .when("/", angularAMD.route({
        templateUrl: function (rp) {
			return settings.VIEWS_DIRECTORY+'/pages/home.'+settings.VIEW_EXTENSION;
		},
		controllerUrl: settings.CTRLS_DIRECTORY+"/page_controller"            
    }))
	.when("/login", angularAMD.route({
        templateUrl: function (rp) {
			return "views/auth/login."+settings.VIEW_EXTENSION;
		},
		controllerUrl: "controllers/auth_controller"            
    }))
	.when("/logout", angularAMD.route({
        templateUrl: function (rp) {
			return "views/auth/login."+settings.VIEW_EXTENSION;
		},
		controllerUrl: "controllers/auth_controller"            
    }))
	
	.when("/pages/:page",angularAMD.route({
		templateUrl: function (rp) {
			return settings.VIEWS_DIRECTORY+'/pages/'+rp.page+'.'+settings.VIEW_EXTENSION;
		},
		controllerUrl:  settings.CTRLS_DIRECTORY+"/page_controller"      
	}))
	
	.when("/:controller", mainRouter)
	.when("/:controller/:action", mainRouter)
        .otherwise({ redirectTo: '/' }) 
    }]);                
	app.config(['$uibModalProvider', function($uibModalProvider) {
		$uibModalProvider.options = {
		  backdrop: 'static',
		};
	  }]);
	app.config(['$logProvider', function($logProvider){
		$logProvider.debugEnabled(settings.DEBUG_MODE);
	}]);
	 app.config(['$provide', function($provide) {
		$provide.decorator('$locale', ['$delegate', function($delegate) {
			console.log($delegate.id);
			$delegate.NUMBER_FORMATS.PATTERNS[1].negPre = '(\u00A4';
			$delegate.NUMBER_FORMATS.PATTERNS[1].negSuf = ')';
			$delegate.NUMBER_FORMATS.CURRENCY_SYM = '';
		  
		  return $delegate;
		}]);
	  }]);
	//Bootstrap RootController
	app.controller('RootController', root);
	//Bootstrap Directives
	for(var drctv in directives)
		app.directive(drctv, directives[drctv]);
    // Bootstrap Angular when DOM is ready
    angularAMD.bootstrap(app);
	
	app.settings =  settings;
    return app;
});