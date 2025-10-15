<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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

	// completa i progetti in lavorazione e libera i developer
	public function checkCompletedProjects(int $time_s): void {
		$completedProjects = $this->projects()->where('status', 'in_progress')->where('remaining_time', '<=', $time_s);
		$totalValue = $completedProjects->sum('value_eur');
		$developerIdsToFree = (clone $completedProjects)->pluck('employee_id')->filter()->unique();
		if ($totalValue > 0) {
			$this->assets_eur += $totalValue;
			$this->save();
		}
		$completedProjects->update(['status' => 'completed', 'remaining_time' => 0, 'employee_id' => null]);
		if ($developerIdsToFree->isNotEmpty()) {
			Employee::whereIn('id', $developerIdsToFree)->update(['status' => 'available']);
		}
	}

	// mette in stato d'attesa i progetti il cui design è terminato e libera i salesman
	public function checkDesignedProjects(int $time_s): void {
		$designedProjects = $this->projects()->where('status', 'in_design')->where('remaining_time', '<=', $time_s);
		$salesIdsToFree = (clone $designedProjects)->pluck('employee_id')->filter()->unique();
    $designedProjects->update(['status' => 'pending', 'employee_id' => null, 'remaining_time' => 0]);
		if ($salesIdsToFree->isNotEmpty()) {
			Employee::whereIn('id', $salesIdsToFree)->update(['status' => 'available']);
		}
	}

	// aggiorna il tempo rimanente di tutti i progetti in design o lavorazione
	public function checkWIPProjects(int $time_s): void {
		$this->projects()->whereIn('status', ['in_progress', 'in_design'])->where('remaining_time', '>', $time_s)
       		           ->update(['remaining_time' => DB::raw("remaining_time - $time_s")]);
	}


	public const EMPLOYEE_COST = 2;
	// calcola e sottrae costi personale, incrementa in base a numero progetti completati
	// restituisce "true" se patrimonio positivo, "false" se siamo in bancarotta
	public function payday(): bool {
		$employeeCount = $this->employees()->count();
		$completedProjectsCount = $this->projects()->where('status', 'completed')->count();
		$updatedEmployeeCost = self::EMPLOYEE_COST * (1 + $completedProjectsCount * 0.1);

		$totalCost = $employeeCount * $updatedEmployeeCost;

		$this->assets_eur -= $totalCost;
		$this->save();

		return $this->assets_eur >= 0;
	}
    
}