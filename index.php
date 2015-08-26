<!DOCTYPE html>
<html>
    <head>
        <title>Weather Checker</title>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.4/angular.min.js"></script>
        <style>
            body {
                font-family: sans-serif;
            }
        </style>
    </head>

    <body ng-app="weatherApp">
      <div ng-controller="weatherController">
        <form name="weatherForm" novalidate>
            Zip Code: <input type="text" name="zipcode" ng-model="zipcode" autocomplete="off" required>
            <br>
            <button ng-click="check(zipcode)" ng-disabled="weatherForm.zipcode.$invalid">
              Check Weather
            </button>
        </form>
        <h2>{{title}}</h2>
        <div ng-bind-html="description"></div>
      </div>

        <script>
            var app = angular.module('weatherApp', []);
            app.controller('weatherController', function($scope, $http, $sce) {

                $scope.check = function(zipcode) {
                    $http.get("/weather.php?zipcode=" + zipcode)
                    .success(function(response) {
                        if (response.error) {
                            $scope.title = response.error;
                        } else {
                            $scope.title = response.title;
                            $scope.description = $sce.trustAsHtml(response.description);
                        }
                    });
                }
            });
        </script>
    </body>
</html>