<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
	use HasFactory;
    public $timestamps=false;

	protected $fillable = [
		'user_id',
		'order_id',
		'review',
		'rating',
        'backup_name',
        'backup_email',
        'created_at'
	];

	protected $appends = [

	];

	protected $with = [
		'user'
	];

	public function user()
    {
        return $this->BelongsTo(User::class);
	}

	public function order()
    {
        return $this->BelongsTo(Order::class);
	}

	public function products()
    {
		return $this->belongsToMany(Product::class)->withTimestamps();
	}
}
