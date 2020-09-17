<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model{
    protected $fillable = [
        'tweet','userid' ,'total_comments','total_likes','total_retweets',
    ];
}
?>