<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
	use HasFactory;
	protected $fillable = [
		'game_id',
		'employee_id',
		'complexity',
		'value_eur',
		'status',
		'remaining_time',
		'completed_at',
	];

	protected $casts = [
		'completed_at' => 'datetime',
	];

	// relazioni con Game
	public function game(): BelongsTo {
		return $this->belongsTo(Game::class);
	}
	
	public function employee(): BelongsTo {
		return $this->belongsTo(Employee::class);
	}
}