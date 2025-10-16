<?php

// app/Http/Controllers/Api/GameController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller {
	// Inizia una nuova partita
	public function startNewGame() {
		$game = DB::transaction(function () {
			$game = Game::whereNot('status', 'bankruptcy')->update(['status' => 'discarded']);
			// Crea una partita con patrimonio 5k
			$game = Game::create(['assets_eur' => 5000]);

			// Aggiunge team iniziale
			$game->employees()->create(['role' => 'developer', 'experience' => 1, 'status' => 'available']);
			$game->employees()->create(['role' => 'sales_man', 'experience' => 1, 'status' => 'available']);

			return $game;
		});

		return response()->json($this->getGameStateData($game));
	}

	// Recupera eventuali partite non terminate dal DB
	public function getPausedGame() {
		$game = Game::whereNot('status', 'bankruptcy')->latest('updated_at')->first();
		if(isset($game)){
			return response()->json($this->getGameStateData($game));
		} else {
			return response()->json([]);
		}
	}

	// Recupera lo stato di una partita salvata 
	public function getGameState(Game $game) {
		return response()->json($this->getGameStateData($game));
	}

	// Assegna un developer a un progetto
	public function assignDeveloper(Game $game, Project $project, Employee $developer) {
		if ($developer->role !== 'developer' || $developer->status !== 'available' || $project->status !== 'pending') {
			return response()->json(['error' => 'Assegnazione non valida.'], 400);
		}

		// Il tempo necessario dipende da complessità del progetto / seniority dello sviluppatore
		$remaining_time = 120*ceil($project->complexity / $developer->experience);

		$project->update([
			'employee_id' => $developer->id,
			'status' => 'in_progress',
			'remaining_time' => $remaining_time
		]);
		
		// Il developer diventa "occupato" 
		$developer->update(['status' => 'busy']);

		return response()->json($this->getGameStateData($game->fresh()));
	}

	// Un commerciale genera un nuovo progetto
	public function generateProject(Game $game, Employee $sales_man) {
		if (empty($sales_man->id)) {
			return response()->json(['error' => $game], 400);
		}
		$complexity = rand(1, 5);
		// il valore economico dipende sia dalla complessità del progetto, sia dall'esperienza del commerciale
		$value_eur = $complexity * 100 * $sales_man->experience;

		// Il tempo necessario alla generazione dipende dall'esperienza del commerciale
		$remaining_time = ceil(180 / $sales_man->experience);
		
		// I progetti in fase di creazione hanno status "in_design"
		$game->projects()->create([
			'complexity' => $complexity,
			'value_eur' => $value_eur,
			'status' => 'in_design',
			'employee_id' => $sales_man->id,
			'remaining_time' => $remaining_time
		]);
		// Il commerciale diventa "occupato" 
		$sales_man->update(['status' => 'busy']);
		
		return response()->json($this->getGameStateData($game->fresh()));
	}

	// Mostra candidati sul mercato
	public function getMarketCandidates() {
		$candidates = [
			['role' => 'developer', 'experience' => rand(1, 5)],
			['role' => 'sales_man', 'experience' => rand(1, 5)]
		];

		// calcola costo di assunzione in base ad esperienza (+ offset per rendere l'esperienza di gioco più realistica)
		foreach ($candidates as &$candidate) {
			$candidate['cost'] = 1000 + $candidate['experience'] * 500;
		}
		return response()->json($candidates);
	}
			
	// Assume un nuovo dipendente
	public function hireEmployee(Request $request, Game $game) {
		$validated = $request->validate(['role' => 'required|in:developer,sales_man', 'experience' => 'required|integer', 'cost' => 'required|integer']);
		$hiringCost = $validated['cost'];

		if ($game->assets_eur < $hiringCost) {
			return response()->json(['error' => 'Patrimonio insufficiente.'], 400);
		}

		// aggiornamento patrimonio per ogni nuova assunzione -> transazione per evitare incongruenze
		DB::transaction(function () use ($game, $validated, $hiringCost) {
			$game->assets_eur -= $hiringCost;
			$game->save();
			$game->employees()->create($validated);
		});

		return response()->json($this->getGameStateData($game->fresh()));
	}

	// recupero dati condiviso
	private function getGameStateData(Game $game) {
		return [
			'id' => $game->id,
			'assets_eur' => $game->assets_eur,
			'developers' => $game->employees()->where('role', 'developer')->get(),
			'sales_men' => $game->employees()->where('role', 'sales_man')->get(),
			'projects' => $game->projects()->with('employee')->get(),
			'status' => $game->status
		];
	}

	// -----aggiornamento stati e risorse di gioco
	// NB: se questo sistema dovesse essere implementato in sistemi utilizzati da più utenti, con eventuali latenze, tempi di elaborazione
	// e necessità di periodi inferiori a 10 secondi, sarebbe preferibile un approccio diverso: utilizzando una connessione WebSocket.
	// Per questo caso inoltre "ci fidiamo" del sincronismo front-back, quindi andiamo a sottrarre esattamente 10 secondi
	public function updateGameState(Game $game) {
		$timeToSubtr = 10;

		// evoluzione risorse e progetti
		$game->checkCompletedProjects($timeToSubtr);
		$game->checkDesignedProjects($timeToSubtr);
		$game->checkWIPProjects($timeToSubtr);

		// check bancarotta
		$stillAlive = $game->payday();
    if (!$stillAlive) {
        $game->status = 'bankruptcy'; // Imposta lo stato a bancarotta
        $game->save();
    }

		return response()->json($this->getGameStateData($game->fresh()));
	}
}