<template>
	<div class="main-menu">
		<h2 v-if="showLostMessage" class="bankruptcy-message">{{ $t('menu.lost_message') }}</h2>
		<h2 v-else>{{ $t('menu.title') }}</h2>
		<br></br>
		<button @click="startNewGame">{{ $t('menu.new_game') }}</button>
		<button v-if="savedGameId" @click="resumeGame">{{ $t('menu.resume_game') }}</button>
	</div>
</template>

<script setup>
	import { useRouter, useRoute } from 'vue-router';
	import { useGameStore } from '@/store';
	import { onMounted, ref } from 'vue';
	import backendReq from '@/api/backendReq';
	import { useI18n } from 'vue-i18n';
    import { useToast } from 'vue-toastification';

	const router = useRouter();
	const gameStore = useGameStore();
	const savedGameId = ref(null);
  	const { t } = useI18n();
	const currentRoute = useRoute();
	const showLostMessage = ref(false);
    const toast = useToast();
	
	onMounted(async () => {
		savedGameId.value = localStorage.getItem('gameId');
		if (currentRoute.query.status === 'lost') {
			showLostMessage.value = true;
		}

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
			toast.error(t('menu.loading_error'));
			savedGameId.value = null;
		}
	};
</script>