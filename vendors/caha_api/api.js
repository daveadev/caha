"use strict";
define(['app','api','atomic/bomb'],function(app){
    app.register.service('cahaApiService', ['$http', function($http) {
        this.uploadSOA = function(pdfUrl, fileName, success,error) {
            let uploadURL = 'https://rainbow.marqa.one/caha-api/upload-soa';
            // Fetch the PDF
            return $http({
                method: 'GET',
                url: pdfUrl,
                responseType: 'blob'
            }).then(function(response) {
                // Convert to Blob
                var pdfBlob = new Blob([response.data], { type: 'application/pdf' });

                // Create FormData
                var formData = new FormData();
                formData.append('file', pdfBlob, fileName);

                // Upload to server
                return $http.post(uploadURL, formData, {
                    transformRequest: angular.identity,
                    headers: { 'Content-Type': undefined }
                }).then(success,error);
            });
        };
        this.updateSOA = function(sno, billMonth,data,success,error){
        	let updateURL = `https://rainbow.marqa.one/caha-api/update-bill/${sno}/${billMonth}`;

        	  // Upload to the server
		    return $http.post(updateURL, data, {
		        headers: { 'Content-Type': 'application/json' } // Use 'application/json' since you're sending JSON
		    }).then(success, error);

        }

        this.updateInfo = function(sno, data,success,error){
            let updateURL = `https://rainbow.marqa.one/caha-api/update-info/${sno}`;
            let studInfo = angular.copy(data);
            return $http.post('api/students.json', studInfo, {
                headers: { 'Content-Type': 'application/json' } // Use 'application/json' since you're sending JSON
            }).then(function(response){
                return $http.post(updateURL, data, {
                headers: { 'Content-Type': 'application/json' } // Use 'application/json' since you're sending JSON
                }).then(success, error);
            });

        }

        this.getPayments = function(sno,status,success,error){
            let getURL = `https://rainbow.marqa.one/proof-api/status_by/${sno}/${status}`;

              // Upload to the server
            return $http.get(getURL, {
                headers: { 'Content-Type': 'application/json' } // Use 'application/json' since you're sending JSON
            }).then(success, error);

        }

        this.getAllPayments = function(status,success,error){
            let getURL = `https://rainbow.marqa.one/proof-api/status/${status}`;

              // Upload to the server
            return $http.get(getURL, {
                headers: { 'Content-Type': 'application/json' } // Use 'application/json' since you're sending JSON
            }).then(success, error);

        }

        this.updatePayment = function(sno,token,data,success,error){
            let updateURL = `https://rainbow.marqa.one/proof-api/update_payment/${sno}/${token}`;

              // Upload to the server
            return $http.post(updateURL,data, {
                headers: { 'Content-Type': 'application/json' } // Use 'application/json' since you're sending JSON
            }).then(success, error);

        }
    }]);
});