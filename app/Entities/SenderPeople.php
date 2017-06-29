<?php
namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class  SenderPeople extends Model
{
    protected $table = 'sender_peoples';
    protected $guarded = [];

    public function senderGroup()
    {
        return $this->belongsTo(SenderGroups::class, 'group_id', 'id');
    }
}