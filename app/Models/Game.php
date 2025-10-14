<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Game extends Model
{
	use HasFactory;
	protected $fillable = [
		'assets_eur',
	];

	// relazione tipo "has_many" di rails
	public function employees(): HasMany {
		return $this->hasMany(Employee::class);
	}

	// relazione tipo "has_many" di rails
	public function projects(): HasMany {
		return $this->hasMany(Project::class);
	}
}