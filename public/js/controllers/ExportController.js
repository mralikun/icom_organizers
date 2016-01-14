app.controller("ExportController" , ["$scope" , "Patcher" , "$rootScope" , function(scope , network , root){
    
    root.$emit("changeTitle" , "Exports");
    scope.view_data = {
        success_msg: "",
        organizers: []
    };
    
    network.set("url" , "/organizers").set("verb" , "get").send().then(function(response){
        scope.view_data.organizers = response.data;
    }).catch(function(error){
        console.log(error);
    }).finally(function(){
        console.log("DONE!");
    });
    
    network.set("url" , "/conference_tasks").set("verb" , "get").send().then(function(response){
        scope.confs_tasks = response.data;
    }).catch(function(error){
        
    }).finally(function(){
        console.log("DONE!!!!")
    });
    
    scope.export_conference = function(id){
        network.set("url" , "/conference_sheet?conference_id="+id).set("verb" , "get").send().then(function(response){
            console.log(response);
        }).catch(function(error){
            console.log(error);
        });
    }
    
    scope.export_organizer = function(email){
        
    }
    
    scope.export_organizers = function(){
        
    }
    
    scope.show_conference_panel = function(){
        scope.view_data.export_type = 1;
    }
    
    scope.show_organizers_panel = function(){
        scope.view_data.export_type = 2;
    }
    
}]);