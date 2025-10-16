const API_URL = import.meta.env.VITE_API_URL;

import axios from 'axios';

export default {
	startNewGame() {
		return axios.post(`${API_URL}/games/new`);
	},
	getPausedGame() {
		return axios.get(`${API_URL}/games/paused`);
	},
	getGameState(gameId) {
		return axios.get(`${API_URL}/games/${gameId}`);
	},
	getMarketCandidates() {
		return axios.get(`${API_URL}/hr/market`);
	},
	hireEmployee(gameId, employeeData) {
		return axios.post(`${API_URL}/games/${gameId}/hr/hire`, employeeData);
	},
	generateProject(gameId, salesManId) {
		return axios.post(`${API_URL}/games/${gameId}/sales/${salesManId}/generate-project`);
	},
	assignDeveloper(gameId, projectId, developerId) {
		return axios.post(`${API_URL}/games/${gameId}/projects/${projectId}/assign/${developerId}`);
	},
	updateGame(gameId) {
        return axios.post(`${API_URL}/games/${gameId}/update`);
    }
};