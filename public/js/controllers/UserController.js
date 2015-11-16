    app
        .controller("UserController" , ["$scope" , "$http" , "$location" , function(scope , request , loc){
            
            if(loc.path().indexOf("create_organizer") !== -1){
                scope.fields = [];
                request.get("/organizer/getAllDepartments")
                    .then(function(resp){
                    scope.fields = resp.data;
                } , function(err){
                    console.log(err);
                });
                scope.Selector = {
                    IDS: [],
                    names: {},
                    selection: false,
                    select: function(_id){
                        obj = {
                            id: _id,
                            name: $("option[value='"+ _id +"']").text()
                        };
                        if(this.IDS.indexOf(obj.id) === -1){
                            this.IDS.push(obj.id);
                            this.names[obj.id] = obj.name;
                        }
                        this.remove(_id);
                        this.updateStatus();
                    },
                    
                    updateStatus: function(){
                        
                        var namesLength = Object.keys(this.names).length
                        
                        if( namesLength > 0 ){
                            this.selection = true;
                        }else
                            this.selection = false;
                        
                    },
                    
                    remove: function(_ID){
                        $(scope.fields).each(function(index , val){
                            if(val.id == _ID){
                                scope.fields.splice(index , 1);
                            }
                        });
                    },
                    deselect: function(tag){
                        var obj = {
                            id: 0,
                            name: tag
                        };
                        
                        for(var key in this.names){
                            if(this.names[key] == tag){
                                obj.id = key;
                            }
                        }
                        if(this.IDS.indexOf(obj.id) !== -1){
                            this.IDS.splice(this.IDS.indexOf(obj.id) , 1);
                            scope.fields.push(obj);
                            delete this.names[obj.id];
                        }
                        this.updateStatus();
                    }
                }
                
            }
            
            scope.createOrganizer = function(){
                scope.newUser.departments = scope.Selector.IDS;
                var file = document.getElementById("agreement").files[0];
                var reader = new FileReader();
                reader.onload = function(res){
                    scope.newUser.agreement = res.target.result;
                }
                try{
                    reader.readAsDataURL(file);
                }catch(exp){}
                console.log(scope.newUser);
            }
            
            
        }]);