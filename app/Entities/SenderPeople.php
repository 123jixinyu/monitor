<?php
namespace App\Entities;

use Illuminate\Database\Eloquent\Model;

class  SenderPeople extends Model
{
    protected $table = 'sender_peoples';
    protected $guarded = [];

    const TYPE_EMAIL=1;
    const TYPE_PHONE=2;
    const TYPE_EMAIL_PHONE=3;

    public function senderGroup()
    {
        return $this->belongsTo(SenderGroups::class, 'group_id', 'id');
    }

    public function getType(){
        return array_get([
            self::TYPE_EMAIL=>'邮箱',
            self::TYPE_PHONE=>'手机',
            self::TYPE_EMAIL_PHONE=>'邮箱+手机'
        ], $this->type);
    }
}