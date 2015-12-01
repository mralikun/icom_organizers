app.controller("TaskController" , ["$scope" , "$rootScope" , "Patcher" , function(scope , root , request){
    
    root.$emit("changeTitle" , "Assign Tasks");
    var ids = [];
    var types = ["conference" , "office hours" , "one time"];
    scope.append = function(_ev){
        var tar = _ev.target;
        var id = parseInt(tar.getAttribute("data-id") , 10);
        if(ids.indexOf(id) === -1)
            ids.push(id);
        else
            ids.splice(ids.indexOf(id) , 1);
    }
    
    scope.load_organizer = function(){
        if(!scope.loaded_organizers){
            request.set("url" , "/organizers").set("verb" , "get").send().then(function(resp){
                scope.organizers = resp.data;
                scope.loaded_organizers = true;
            } , function(err){
                alert("Something wrong happend, Please refresh and try again!");
            });
        }
    }
    
    scope.getConferences = function(){
        if(scope.start instanceof Date){
            if(scope.end < scope.start){
                scope.wrong_dates = true;
                return false;
            }else{
                scope.wrong_dates = false;
                request.set("url" , "/conference").set("verb" , "get").set("data" , {from: scope.start , to: scope.end}).send().then(function(resp){
                    scope.confs = resp.data;
                } , function(err){
                    alert("Something went wrong while retrieving conferences data, Please refresh and try again!");
                });
            }
        }
    }
    
    scope.create = function(){
        if(!("type" in scope.task)){
            alert("Please choose a task type!");
            return false;
        }
        var data = angular.copy(scope.task);
        data.type = types[data.type - 1];
        console.log(data);
//            request.set("url" , "/task").set("verb" , "post").set("data" , {}).send().then(function(resp){} , function(err){});
    }
    
}]);