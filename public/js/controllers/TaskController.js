app.controller("TaskController" , ["$scope" , "$rootScope" , "Patcher" , function(scope , root , request){
    
    root.$emit("changeTitle" , "Assign Tasks");
    
    var ids = [];
    scope.append = function(_ev){
        var tar = _ev.target;
        var id = parseInt(tar.getAttribute("data-id") , 10);
        if(ids.indexOf(id) === -1)
            ids.push(id);
        else
            ids.splice(ids.indexOf(id) , 1);
    }
    
    request.set("url" , "/organizers").set("verb" , "get").send().then(function(resp){
        scope.organizers = resp.data;
    } , function(err){
        alert("Something wrong happend, Please refresh and try again!");
    });
    
}]);