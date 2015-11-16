    app
        .controller("UserController" , ["$scope" , "$http" , "$location" , function(scope , request , loc){
            
            if(loc.path().indexOf("create_organizer") !== -1){
                request.get("/organizers/getAllDepartments")
                    .then(function(resp){
                    console.log(resp.data);
                } , function(err){
                    console.log(err);
                });
                scope.fields = [{id:5 , name:"YO"}];
                scope.Selector = {
                    IDS: [],
                    names: {},
                    select: function(obj){
                        console.log(obj);
//                        if(this.IDS.indexOf(obj.id) === -1){
//                            this.IDS.push(obj.id);
//                            this.names[obj.id] = obj.name;
//                        }
                    },
                    deselect: function(obj){
                        console.log(obj);
//                        if(this.IDS.indexOf(obj.id) !== -1){
//                            this.IDS.splice(this.IDS.indexOf(obj.id) , 1);
//                            delete this.names[obj.id];
//                        }
                    }
                }
                
            }
            
            scope.createOrganizer = function(){
                
            }
            
            
        }]);