<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model{
    protected $fillable = [
        'tweet' , 'userid' ,
    ];
}
?>