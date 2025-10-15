<template>
	<div class="main-menu">
		<h2>Menu Principale</h2>
		<button @click="startNewGame">Inizia Nuova Partita</button>
		<button v-if="savedGameId" @click="resumeGame">Riprendi Partita Salvata</button>
	</div>
</template>

<script setup>
	import { useRouter } from 'vue-router';
	import { useGameStore } from '@/store';
	import { onMounted, ref } from 'vue';
	import backendReq from '@/api/backendReq';

	const router = useRouter();
	const gameStore = useGameStore();
	const savedGameId = ref(null);

	onMounted(async () => {
		savedGameId.value = localStorage.getItem('gameId')
		if(!savedGameId.value) {
			const response = await backendReq.getPausedGame();
			savedGameId.value = localStorage.getItem('gameId') || response.data.id;
		}
	});

	const startNewGame = async () => {
	await gameStore.newGame();
	router.push(`/game/${gameStore.gameId}/production`);
	};

	const resumeGame = async () => {
		if (await gameStore.loadGame(savedGameId.value)) {
			router.push(`/game/${gameStore.gameId}/production`);
		} else {
			alert('Impossibile caricare la partita. Iniziane una nuova.');
			savedGameId.value = null;
		}
	};
</script>