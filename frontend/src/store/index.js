// src/store/index.js
import { defineStore } from 'pinia';
import backendReq from '@/api/backendReq';

export const useGameStore = defineStore('game', {
	state: () => ({
		gameId: null,
		assets_eur: 0,
		developers: [],
		sales_men: [],
		projects: [],
		status: 'in_progress',
		isSidebarOpen: false
	}),

	getters: {
		availableDevelopers: (state) => state.developers.filter(d => d.status === 'available'),
		availableSales: (state) => state.sales_men.filter(s => s.status === 'available'),
		pendingProjects: (state) => state.projects.filter(p => p.status === 'pending'),
		designingProjects: (state) => state.projects.filter(p => p.status === 'in_design'),
		inProgressProjects: (state) => state.projects.filter(p => p.status === 'in_progress'),
	},

	actions: {
		// helper -> aggiornamento stato game
		_updateGameState(gameState) {
			this.gameId = gameState.id;
			this.assets_eur = parseFloat(gameState.assets_eur);
			this.developers = gameState.developers;
			this.sales_men = gameState.sales_men;
			this.projects = gameState.projects;
			this.status = gameState.status;

			if (this.status === 'bankruptcy') {
				localStorage.removeItem('gameId');
			} else {
				localStorage.setItem('gameId', this.gameId);
			}
		},

		toggleSidebar() {
			this.isSidebarOpen = !this.isSidebarOpen;
		},

		async newGame() {
			const response = await backendReq.startNewGame();
			this._updateGameState(response.data);
		},

		async loadGame(gameId) {
			try {
				const response = await backendReq.getGameState(gameId);
				this._updateGameState(response.data);
				return true;
			} catch (error) {
				console.error("Partita non trovata:", error);
				localStorage.removeItem('gameId');
				return false;
			}
		},
		
		async hire(employeeData) {
			console.log('hireEmployee', employeeData);
			const response = await backendReq.hireEmployee(this.gameId, employeeData);
			this._updateGameState(response.data); // Aggiorna il patrimonio
		},

		async generate(salespersonId) {
			const response = await backendReq.generateProject(this.gameId, salespersonId);
			this._updateGameState(response.data);
		},

		async assign(projectId, developerId) {
			const response = await backendReq.assignDeveloper(this.gameId, projectId, developerId);
			this._updateGameState(response.data);
		},

		async fetchUpdates() {
			// console.log("fetchUpdates, this:", this);
			if (!this.gameId) return;
		
			try {
				const response = await backendReq.updateGame(this.gameId);
				this._updateGameState(response.data);
				if (this.status === 'bankruptcy') {
					// console.log('bankruptcy!!!');
					localStorage.removeItem('gameId');
					return 'bankruptcy';
				}
			} catch (error) {
				console.error("Errore fetchUpdates", error);
			}
		},
	}
});