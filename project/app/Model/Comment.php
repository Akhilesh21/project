<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model{
    protected $fillable = [
        'comment' , 'twitter_handler' ,'tweet_id',
    ];
}
?>