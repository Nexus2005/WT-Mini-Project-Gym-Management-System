// AngularJS module for Login & Register
var app = angular.module('GymAuthApp', []);

app.controller('AuthController', ['$scope', '$http', function($scope, $http) {
    $scope.user = {};
    $scope.errorMessage = '';

    $scope.login = function() {
        if ($scope.loginForm.$invalid) {
            alert("Please fill all fields");
            return;
        }

        $http.post('api/login_process.php', $scope.user)
            .then(function(response) {
                if (response.data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    $scope.errorMessage = response.data.message || 'Invalid credentials';
                }
            }, function(error) {
                $scope.errorMessage = 'Server error occurred.';
            });
    };
}]);

app.controller('RegisterController', ['$scope', '$http', function($scope, $http) {
    $scope.user = {};
    $scope.errorMessage = '';
    
    $scope.validateName = function(name) {
        if (!name) return false;
        return /^[a-zA-Z\s]+$/.test(name);
    };

    $scope.register = function() {
        if ($scope.registerForm.$invalid) {
            alert("Please fill all required fields correctly");
            return;
        }

        if (!$scope.validateName($scope.user.username)) {
            $scope.errorMessage = 'Name can only contain letters and spaces';
            return;
        }

        if ($scope.user.password !== $scope.user.confirm_password) {
            $scope.errorMessage = 'Passwords do not match';
            return;
        }

        $http.post('api/register_process.php', $scope.user)
            .then(function(response) {
                if (response.data.success) {
                    alert("Registration successful! Please login.");
                    window.location.href = 'index.php';
                } else {
                    $scope.errorMessage = response.data.message || 'Registration failed';
                }
            }, function(error) {
                $scope.errorMessage = 'Server error occurred.';
            });
    };
}]);
