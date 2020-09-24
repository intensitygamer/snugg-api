<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BrokerDetail extends Model
{
    //
    protected $fillable = [
        'user_id',
        'subscription_id', 
        'id_status',
        'prc_id',
        'expiration_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->belongsToMany(Image::class, 'id_images', 'broker_detail_id', 'images_id');
    }
}
