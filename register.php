<?php
session_start();
require_once 'config/db.php';
// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="GymAuthApp">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FitZone Gym</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #1a1f36; padding: 20px; }
        .auth-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); width: 100%; max-width: 450px; }
        .auth-card h2 { text-align: center; color: #27ae60; margin-bottom: 30px; font-weight: 600; }
        .auth-card .form-control { margin-bottom: 5px; }
        .auth-card .btn { width: 100%; margin-top: 15px; }
        .text-center { text-align: center; margin-top: 15px; }
        .text-center a { color: #1a1f36; text-decoration: none; font-size: 0.9rem; }
        .text-center a:hover { text-decoration: underline; }
        [ng\:cloak], [ng-cloak], .ng-cloak { display: none !important; }
    </style>
</head>
<body ng-controller="RegisterController" ng-cloak>
    <div class="auth-card">
        <h2>Admin Register</h2>
        <div ng-show="errorMessage" class="flash-alert flash-error" ng-cloak>
            {{errorMessage}}
        </div>
        <form name="registerForm" ng-submit="register()" novalidate>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" ng-model="user.username" required
                       ng-pattern="/^[a-zA-Z\s]+$/" ng-class="{'input-error': registerForm.username.$invalid && registerForm.username.$touched}">
                <div ng-show="registerForm.username.$invalid && registerForm.username.$touched" class="form-error">
                    <span ng-show="registerForm.username.$error.required">Name is required.</span>
                    <span ng-show="registerForm.username.$error.pattern">Only letters and spaces allowed.</span>
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" ng-model="user.email" required 
                       ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]+$/" ng-class="{'input-error': registerForm.email.$invalid && registerForm.email.$touched}">
                <div ng-show="registerForm.email.$invalid && registerForm.email.$touched" class="form-error">
                    <span ng-show="registerForm.email.$error.required">Email is required.</span>
                    <span ng-show="registerForm.email.$error.pattern">Valid email format required.</span>
                </div>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" ng-model="user.password" required ng-minlength="6"
                       ng-class="{'input-error': registerForm.password.$invalid && registerForm.password.$touched}">
                <div ng-show="registerForm.password.$invalid && registerForm.password.$touched" class="form-error">
                    <span ng-show="registerForm.password.$error.required">Password is required.</span>
                    <span ng-show="registerForm.password.$error.minlength">Minimum 6 characters.</span>
                </div>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" ng-model="user.confirm_password" required
                       ng-class="{'input-error': registerForm.confirm_password.$invalid && registerForm.confirm_password.$touched}">
            </div>
            
            <button type="submit" class="btn" ng-disabled="registerForm.$invalid">Register</button>
        </form>
        
        <div class="text-center">
            <a href="index.php">Already have an account? Login</a>
        </div>
    </div>
</body>
</html>
