<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable=['original_name','user_id','stored_path','file_type','is_public'];

    public function user(){
return $this->belongsTo(User::class);
    }
}
