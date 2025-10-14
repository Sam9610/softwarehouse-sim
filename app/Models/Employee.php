<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model {
	use HasFactory;
	protected $fillable = [
		'game_id',
		'role',
		'status',
		'experience'
	];

	// simile a belongs_to di rails
	public function game(): BelongsTo {
		return $this->belongsTo(Game::class);
	}
}