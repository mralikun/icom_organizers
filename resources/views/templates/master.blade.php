<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ICOM Organizers</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/fa/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/main.css">
  </head>

  <body ng-app="organizers">

    <div class="container-fluid" ng-controller="UserController as uc">
        
        <header class="navbar navbar-default">
           <button type="button" class="menu-button" ng-show="user.logged_in">
                <i class="fa fa-bars"></i>
            </button>
           <div class="row">
                <div class="col-xs-4 col-xs-offset-8 col-sm-4 col-sm-offset-4 col-md-4 col-lg-4 text-center">
                    <a href="#"><img src="/icons/logo.png" alt="ICOM organizers logo" class="img-responsive logo"></a>
                </div>

                <div class="col-xs-0 heading">ICOM Organizers</div>
            </div>
            
            <nav>

                <div>

                    <img src="/icons/navlogo.png" alt="Logo" class="img-responsive">

                </div>

                <ul>

                    <li><a href="#">Add Organizer</a></li>
                    <li><a href="#">Manage Organizers</a></li>
                    <li><a href="#">Assign Tasks</a></li>
                    <li><a href="#">Grade Organizers</a></li>
                    <li><a href="#">Export Reports</a></li>
                    <li><a href="#">Organizers Attendance</a></li>
                    <li><a href="#">Logout</a></li>

                </ul>

            </nav>
            
        </header>

        <main>
            
            <div ng-view></div>
            
        </main>
        
    </div>

    <script src="/js/core/jquery.min.js"></script>
    <script src="/js/core/angular.min.js"></script>
    <script src="/js/core/angular-route.min.js"></script>
    <script src="/js/core/bootstrap.min.js"></script>
    <script src="/js/app.js"></script>
    <script src="/js/routes.js"></script>
    <script src="/js/controllers/UserController.js"></script>
  </body>

</html>
