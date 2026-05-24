<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    <title>Login - FitZone Gym</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.8.2/angular.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/app.js"></script>
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #1a1f36; }
        .auth-card { background: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); width: 100%; max-width: 400px; }
        .auth-card h2 { text-align: center; color: #27ae60; margin-bottom: 30px; font-weight: 600; }
        .auth-card .form-control { margin-bottom: 5px; }
        .auth-card .btn { width: 100%; margin-top: 15px; }
        .text-center { text-align: center; margin-top: 15px; }
        .text-center a { color: #1a1f36; text-decoration: none; font-size: 0.9rem; }
        .text-center a:hover { text-decoration: underline; }
        [ng\:cloak], [ng-cloak], .ng-cloak { display: none !important; }
    </style>
</head>
<body ng-controller="AuthController" ng-cloak>
    <div class="auth-card">
        <h2>FitZone Login</h2>
        <div ng-show="errorMessage" class="flash-alert flash-error" ng-cloak>
            {{errorMessage}}
        </div>
        <form name="loginForm" ng-submit="login()" novalidate>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" ng-model="user.email" required 
                       ng-pattern="/^[^\s@]+@[^\s@]+\.[^\s@]+$/" ng-class="{'input-error': loginForm.email.$invalid && loginForm.email.$touched}">
                <div ng-show="loginForm.email.$invalid && loginForm.email.$touched" class="form-error">
                    <span ng-show="loginForm.email.$error.required">Email is required.</span>
                    <span ng-show="loginForm.email.$error.pattern">Valid email format required.</span>
                </div>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" ng-model="user.password" required ng-minlength="6"
                       ng-class="{'input-error': loginForm.password.$invalid && loginForm.password.$touched}">
                <div ng-show="loginForm.password.$invalid && loginForm.password.$touched" class="form-error">
                    <span ng-show="loginForm.password.$error.required">Password is required.</span>
                    <span ng-show="loginForm.password.$error.minlength">Minimum 6 characters.</span>
                </div>
            </div>
            
            <button type="submit" class="btn" ng-disabled="loginForm.$invalid">Login</button>
        </form>
        
        <div class="text-center">
            <a href="register.php">Create an Account</a> | <a href="#" onclick="alert('Please contact the IT administrator to reset your password.')">Forgot Password?</a>
        </div>
    </div>
</body>
</html>
