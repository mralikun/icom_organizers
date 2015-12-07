<!DOCTYPE html>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ICOM Organizers</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/fa/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/icom.css">
  </head>

  <body ng-app="organizers">

    <div class="container-fluid" ng-controller="UserController as uc">
        
        <header>
           <button type="button" class="menu-button">
                <i class="fa fa-bars"></i>
            </button>
           <div class="logo-holder">
                <a href="#/" class="home"><img src="/icons/logo.png" alt="ICOM organizers logo" class="logo"></a>
            </div>
            
            <nav>
               
                <div>
                    <a href="#/" class="home"><img src="/icons/navlogo.png" alt="Logo" class="img-responsive"></a>
                </div>

                <ul>

                   @if(Auth::user()->role == "admin" || Auth::user()->role == "operations")
                    <li><a href="#/create_organizer">Add Organizer</a></li>
                    <li><a href="#/search_organizer">Manage Organizers</a></li>
                   @endif
                   

                    <li><a href="#/request_organizers">Request Organizers</a></li>
                    
                    @if(Auth::user()->role == "admin" || Auth::user()->role == "operations")
                    <li><a href="#/requests">View Requests</a></li>
                    <li><a href="#/assign_tasks">Assign Tasks</a></li>
                    @endif
                    

<!--                    <li><a href="#/grade">Grade Organizers</a></li>-->
<!--                    <li><a href="#">Organizers Attendance</a></li>-->
                    <li><a href="/auth/logout">Logout</a></li>

                </ul>

            </nav>
        </header>
        <div class="tail"><h4 class="text-center">@{{view_data.page_title}}</h4></div>
        <main class="container-fluid">
            
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
    <script src="/js/controllers/TaskController.js"></script>
    <script src="/js/controllers/RequestController.js"></script>
    <script>
      app.constant("_TOKEN" , "{{csrf_token()}}");
      </script>
  </body>

</html>
