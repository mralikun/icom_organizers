    app
        .controller("UserController" , ["$scope" , "$http" , function(scope , request){
            
            scope.user = {
                logged_in: false
            };
            
            scope.login = function() {
                
                request.post("/auth/login" , scope.login_data)
                    .then(function(resp){
                    var data = resp.data;
                    if(data == false){
                        
                    }else {
                        console.log("")
                        scope.user.logged_in = true;
                    }
                    
                } , function(err){
                    
                    console.log(err);
                    
                });
                
            }
            
        }]);