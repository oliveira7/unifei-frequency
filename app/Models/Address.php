<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use SoftDeletes;
    
    protected $table = "adresses";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street', 'district', 'city', 'cep', 'number', 'complement'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
