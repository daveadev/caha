require.config({
    baseUrl:'app',
	urlArgs :(function(){
    var metaVersion = document.querySelector('meta[name="version"]').getAttribute('content');
    var d =  new Date();
    var c=metaVersion || 'v1.3.060i';
    
    if(window.location.hostname=="localhost")
        c+='.'+(new Date().valueOf().toString().substr(9));
    
    return c;

    }()),
	waitSeconds: 60,
    paths: {     
        'app': '../config/app',
        'settings': '../config/settings',
		'demo': 'config/demo',
        'model': 'config/model',
        'angular': 'vendors/bower_components/angular/angular.min',
        'angularAMD': 'vendors/bower_components/angularAMD/angularAMD.min',
		'angular-route': 'vendors/bower_components/angular-route/angular-route.min',
        'angular-cookies': 'vendors/bower_components/angular-cookies/angular-cookies.min',
        'angular-local-storage': 'vendors/bower_components/angular-local-storage/dist/angular-local-storage.min',
		'ui-bootstrap' : 'vendors/bower_components/angular-bootstrap/ui-bootstrap-tpls.min',
        'ngload': 'vendors/bower_components/angularAMD/ngload.min', 
        'ui.tree': 'vendors/bower_components/angular-ui-tree/dist/angular-ui-tree', 
		'root': 'controllers/root_controller',
		'directives': 'directives/bootstrap_directive',
		'api': 'controllers/api_controller',
		'moment':'vendors/node_modules/moment/moment',
        'chart':'vendors/node_modules/chart.js/dist/Chart.min',
        'angular-chart':'vendors/node_modules/angular-chart.js/dist/angular-chart',
		'simple-sheet':'../directives/simple_sheet',
        'custom-window':'vendors/custom_window',
        'atomic':'vendors/atomic_design',
		'util':'../config/util',
		'report':'../controllers/report',
		'admin':'../controllers/admin',
        'main':'../controllers/main',
		'ngFileUpload': '../vendors/excel-reader/ng-file-upload.min',
		'xlsx': '../vendors/excel-reader/xlsx.full.min',
        'exceljs':'../vendors/node_modules/exceljs/dist/exceljs',
		'jszip': '../vendors/excel-reader/jszip',
        'transact':'../utils/transactions',
        'fee':'../utils/fees',
        'booklet':'../utils/booklets',
        'adjust-memo':'../utils/adjust_memo',
        'caha':'../vendors/caha_api'
    },
    shim: {
		'angular' : {exports : 'angular'},
        'angular-route': ['angular'],
        'angular-local-storage': ['angular'],         
		'angular-cookies': ['angular'],         
		'angularAMD': ['angular'],
        'ui-bootstrap': ['angular'],
        'ui.tree': ['angular'],
		'angular-chart': ['angular','chart'],
        'custom-window': ['angular'],
    },
    deps: ['app']
});