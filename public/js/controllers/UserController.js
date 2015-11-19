    app
        .controller("UserController" , ["$scope" , "$http" , "$location" , "UserToEdit" , "$timeout" , function(scope , request , loc , user , timeout){
            scope.fields = [];
            scope.processing = false;
            scope.success_msg = undefined;
            scope.pageTitle = "Add Organizer";
            scope.codex_msg = "It might take a few seconds to encode the agreement image after you choose it, Please wait for the encoding to finish before submitting!";
            scope.editMode = (user.data == null) ? false : true;
            var reader = new FileReader();
            reader.onload = function(res){
                scope.$apply(function(){
                    scope.newUser.agreement = res.target.result;
                    scope.codex_msg = "Finished Encoding!";
                });
            }
            $("#agreement").on("change" , function(){
                var file = this.files[0];
                scope.$apply(function(){
                    scope.codex_msg = "encoding image... ";
                });
                reader.readAsDataURL(file);
            });
            if(user.data != null)
                scope.pageTitle = "Edit Organizer";
            scope.Selector = {
                IDS: [],
                names: {},
                selection: false,
                mine: function(_par){
                    
                    var matches = null;
                    
                    if(isNaN(_par)){
                        var o = {};
                        for(var k in this.names){
                            if(this.names[k] == _par){
                                o.id = k;
                                o.name = this.names[k];
                            }
                        }
                        matches = [o];
                        
                    }else {
                        
                        matches = filter(scope.fields , function(element){
                            var res = false;
                            var parsed = parseInt(_par , 10);
                            return element.id == _par;
                        });
                        
                    }
                    
                    if(matches.length)
                        return (matches.length > 1) ? matches : matches[0];
                    else
                        return null;
                    
                },
                
                default: function(){
                    for(var k in this.names){
                        this.deselect(this.names[k]);
                    }
                },
                
                select: function(_id){
                    
                    var obj = this.mine(_id);
                    if(obj !== null){
                        this.remove(obj.id);
                        this.attach(obj);
                    }
                    this.updateStatus();
                    
                },
                
                deselect: function(_tag){
                    var obj = this.mine(_tag);
                    if(obj !== null){
                        this.add(obj);
                        this.detach(obj.id);
                    }
                    this.updateStatus();
                },
                
                remove : function(_id){
                    $(scope.fields).each(function(index , val){
                        if(val.id == _id){
                            scope.fields.splice(index , 1);
                        }
                    });
                },
                
                attach: function(_o){
                    if(this.IDS.indexOf(_o.id) === -1){
                        this.IDS.push(_o.id);
                        this.names[_o.id] = _o.name;
                    }
                },
                
                add: function(_o){
                    scope.fields.push(_o);
                },
                
                detach: function(_id){
                    delete this.names[_id];
                    this.IDS.splice(this.IDS.indexOf(_id) + 1 , 1);
                },
                
                updateStatus: function(){

                    var namesLength = Object.keys(this.names).length
                    if( namesLength > 0 ){
                        this.selection = true;
                    }else
                        this.selection = false;
                }
            }
            request.get("/organizer/getAllDepartments")
                .then(function(resp){
                scope.fields = resp.data;
                if(user.data != null){
                    for(var i = 0; i < user.data.departments_ids.length ; i++){
                        scope.Selector.select(user.data.departments_ids[i].id);
                    }
                }
            } , function(err){
                alert("Something went wrong while retriving departments data, Please refresh and try again!");
            });
            
            if(user.data != null){
                user.data.dob = new Date(user.data.dob);
                scope.newUser = user.data;
                delete scope.newUser.agreement;
            }
            
            scope.editUser = function(){
                request.post("/organizer/update/"+scope.newUser.id , scope.newUser)
                    .then(function(resp){
                        scope.processing = false;
                        var response = resp.data;
                        if(response instanceof Object){
                            alert("Some error occurred, Please review the list of errors below!");
                            scope.errors = response;
                        }else {
                            scope.success_msg = "Edited Successfully!";
                            user.reset();
                            timeout(function(){
                                scope.success_msg = undefined;
                            } , 2000);
                        }
                    
                } , function(err){});
                
            }
            
            scope.createOrganizer = function(){
                scope.errors = [];
                if(document.getElementById("agreement").files.length){
                    if(!scope.newUser.agreement || scope.newUser.agreement.indexOf("base64") === -1){
                        return false;
                    }
                }
                scope.processing = true;
                scope.newUser.departments = scope.Selector.IDS;
                if(user.data != null){
                    scope.editUser();
                    return false;
                }

                request.post("/organizers" , scope.newUser).then(function(resp){
                    scope.processing = false;
                    var response = resp.data;
                    if(response instanceof Object){
                        alert("Some error occurred, Please review the list of errors below!");
                        scope.errors = response;
                    }else {
                        scope.codex_msg = "It might take a few seconds to encode the agreement image after you choose it, Please wait for the encoding to finish before submitting!";
                        scope.success_msg = "Created Successfully!";
                        delete scope.newUser;
                        scope.Selector.default();
                        timeout(function(){
                            scope.success_msg = undefined;
                        } , 2000);
                    }
                } , function(err){});
            }
            
        }]);