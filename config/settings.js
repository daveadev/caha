define(function() {
  return {
	DEMO_MODE: false,
	CTRLS_DIRECTORY: '../controllers',
	VIEWS_DIRECTORY: 'views',
	VIEW_EXTENSION: (
		function(){
			var ext = 'html?'+ document.querySelector('meta[name="version"]').getAttribute('content');

			if(window.location.hostname=="localhost")
				ext+='.'+(new Date().valueOf().toString().substr(9));
			
			return ext;
		})(),
	APP_TRANSITION_DELAY: 111,
	FAB_TRANSITION_DELAY: 333,
	DEFAULT_MODULE_NAME: 'Module',
	TEST_DELAY: 0,
	TEST_DIRECTORY:'../tests',
	TEST_SUCCESS:true,
	TEST_ERROR:false,
	MAX_IDLE: (function(){
		var isLocal = window.location.origin.indexOf("localhost")!==-1;
		var mins =  isLocal?15:15;
		var maxIdle = 1000 * 60 * mins;  
		return maxIdle;
	})(),
	SESS_INTERVAL: 1000 * 30 * 10, // 10 mins.
	COOKIE_EXPIRY: 90*60*1000 ,// 1 hr 30 mins. 
	HTTP_CONF: {timeout:1000*10},
	API_URL:'api/',
	API_EXT:'json',
	API_HOST:window.location.origin+window.location.pathname,
  };
 });