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
    
    function date_formater(d){
        var day = d.getDate();
        var month = d.getMonth();
        var year = d.getFullYear();
        return year + "-" + ((month < 10) ? "0"+month : month) + "-" + ((day < 10) ? "0"+day : day);
    }
    
    scope.grading_conferences = function(){
        var start = (scope.start) ? $.datepicker.parseDate("dd-mm-yy" , scope.start) : "";
        var end = (scope.end) ? $.datepicker.parseDate("dd-mm-yy" , scope.end) : "";
        if(start instanceof Date && end instanceof Date){
            if(end < start){
                scope.wrong_dates = true;
                return false;
            }else{
                scope.wrong_dates = false;
                var params = "from="+date_formater(start)+"&to="+date_formater(end);
                network.set("url" , "/conferences?"+params).set("verb" , "get").send().then(function(resp){
                    scope.confs = resp.data;
                } , function(err){});
            }
        }else {}
    }
    
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