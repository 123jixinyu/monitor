<?php
namespace App\Repository;

use App\User;
use Illuminate\Contracts\Routing\UrlGenerator;
use JeroenNoten\LaravelAdminLte\Menu\Builder;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Auth;

class FilterRepository  implements FilterInterface
{

    public function transform($item, Builder $builder)
    {
        $user=Auth::user();
        if($user->privilege==User::ADMIN_NO){
            if(isset($item['url'])&&in_array($item['url'],config('adminlte.admin_route'))){
                return false;
            }
        }
        return $item;
    }
}