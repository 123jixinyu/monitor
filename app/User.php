<?php

namespace App;

use App\Entities\UserMonitor;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    //是否是管理员
    const ADMIN_YES = 1;
    const ADMIN_NO = 0;
    //是否被禁止登录
    const LOGIN_STATUS_FORBIDDEN = 1;
    const LOGIN_STATUS_NORMAL = 0;
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
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public function userMonitor()
    {
        return $this->hasMany(UserMonitor::class, 'user_id', 'id');
    }

    public function getLoginStatus()
    {
        return array_get(['正常', '已被禁止'],
            $this->login_status);
    }
    
    public function getLoginStatusAction()
    {
        return array_get(['禁止登录', '取消登录禁止'],
            $this->login_status);
    }
}
