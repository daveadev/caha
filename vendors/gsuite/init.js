define(['app'], function(app){

 	  // Client ID and API key from the Developer Console
      const CLIENT_ID = app.settings.G_SUITE_CREDENTIALS.web.client_id;
      const API_KEY = app.settings.G_SUITE_API_KEY;

      // Array of API discovery doc URLs for APIs used by the quickstart
      //const DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/admin/directory_v1/rest","https://www.googleapis.com/discovery/v1/apis/classroom/v1/rest","https://www.googleapis.com/discovery/v1/apis/admin/reports_v1/rest"];
      const DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/admin/directory_v1/rest","https://www.googleapis.com/discovery/v1/apis/classroom/v1/rest"];

      // Authorization scopes required by the API; multiple scopes can be
      // included, separated by spaces.
      //const SCOPES = 'https://www.googleapis.com/auth/admin.directory.user.readonly https://www.googleapis.com/auth/admin.directory.orgunit.readonly https://www.googleapis.com/auth/admin.directory.user https://www.googleapis.com/auth/classroom.courses https://www.googleapis.com/auth/classroom.rosters https://www.googleapis.com/auth/classroom.coursework.students.readonly https://www.googleapis.com/auth/admin.reports.usage.readonly https://www.googleapis.com/auth/admin.reports.audit.readonly';
      const SCOPES = 'https://www.googleapis.com/auth/admin.directory.user https://www.googleapis.com/auth/classroom.courses https://www.googleapis.com/auth/classroom.rosters https://www.googleapis.com/auth/classroom.coursework.students.readonly';

      var authorizeButton,signoutButton;
      var signInListener;

      /**
       * On load, called to load the auth2 library and API client library.
       */
      function handleClientLoad(listener) {
      	signInListener = listener;
      	authorizeButton = document.getElementById('authorize_button');
      	signoutButton = document.getElementById('signout_button');
        console.log("INIT",gapi.load);
        if(gapi.load){
          console.log("Attempt gapi.load", new Date());
          gapi.load('client:auth2', initClient);
        }

      }

      /**
       *  Initializes the API client library and sets up sign-in state
       *  listeners.
       */
      function initClient() {
        gapi.client.init({
          apiKey: API_KEY,
          clientId: CLIENT_ID,
          discoveryDocs: DISCOVERY_DOCS,
          scope: SCOPES
        }).then(function () {
          // Listen for sign-in state changes.

          gapi.auth2.getAuthInstance().isSignedIn.listen(updateSigninStatus);

          // Handle the initial sign-in state.
          updateSigninStatus(gapi.auth2.getAuthInstance().isSignedIn.get());
          if(authorizeButton &&signoutButton){
            authorizeButton.onclick = handleAuthClick;
            signoutButton.onclick = handleSignoutClick;
          }
        }, function(error) {
          appendPre(JSON.stringify(error, null, 2));
        });
      }

      /**
       *  Called when the signed in status changes, to update the UI
       *  appropriately. After a sign-in, the API is called.
       */
      function updateSigninStatus(isSignedIn) {
      	if(signInListener)
      		signInListener(isSignedIn);
        if (isSignedIn) {
          if(authorizeButton &&signoutButton){
            authorizeButton.style.display = 'none';
            signoutButton.style.display = 'block'; 
          }
        } else {
          authorizeButton.style.display = 'block';
          signoutButton.style.display = 'none';
        }
      }

      /**
       *  Sign in the user upon button click.
       */
      function handleAuthClick(event) {
        gapi.auth2.getAuthInstance().signIn();
      }

      /**
       *  Sign out the user upon button click.
       */
      function handleSignoutClick(event) {
        gapi.auth2.getAuthInstance().signOut();
      }

      /**
       * Append a pre element to the body containing the given message
       * as its text node. Used to display the results of the API call.
       *
       * @param {string} message Text to be placed in pre element.
       */
      function appendPre(message) {
        var pre = document.getElementById('content');
        var textContent = document.createTextNode(message + '\n');
        if(pre) pre.appendChild(textContent);
      }

      /**
       * Print the first 10 users in the domain.
       */
      function listUsers() {
        gapi.client.directory.users.list({
          'customer': 'my_customer',
          'maxResults': 10,
          'orderBy': 'email'
        }).then(function(response) {
          var users = response.result.users;
          appendPre('Users:');

          if (users && users.length > 0) {
            for (i = 0; i < users.length; i++) {
              var user = users[i];
              appendPre('-' + user.primaryEmail + ' (' + user.name.fullName + ')');
            }
          } else {
            appendPre('No users found.');
          }
        });
      }
      // gapi offline handling
      //var gapi = gapi ||{};
      return{
      	handleClientLoad:handleClientLoad,
      	handleAuthClick:handleAuthClick,
      	handleSignoutClick:handleSignoutClick,
      	api:gapi,
      };

});