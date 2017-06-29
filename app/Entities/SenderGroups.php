<?php
namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class SenderGroups extends Model
{
    protected $table = 'sender_groups';
    protected $guarded = [];

    public function senderPeople()
    {
        return $this->hasMany(SenderPeople::class, 'group_id', 'id');
    }
}