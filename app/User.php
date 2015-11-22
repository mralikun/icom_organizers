<?php  
namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'role', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    
	/**
     * return all orgaizers that can work in specific Deparment
     * use this function for users that have role "department"
     *
     */
    public function organizers(){
        return $this->belongsToMany('App\Organizer','department_organizer', 'user_id' , 'organizer_id');
    }

    /**
     * Get All Departments to be Listed in the "Working Fields" DropDown list
     * This used in Add Organizer And Edit Organizer
     *
     * @param  int  $id
     * @return Response
     */
    public function getAllDepartments(){

        $departments = $this->where('role',"=","department")->get();

        return $departments;

    }

}
