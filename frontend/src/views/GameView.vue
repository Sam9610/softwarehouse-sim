<template>
  <div v-if="gameStore.gameId">
    <div class="game-header">
      <h3>Patrimonio: €{{ gameStore.assets_eur.toFixed(2) }}</h3>
    </div>
    <div class="screen-container">
      <router-view />
    </div>
    <Navigation />
  </div>
  <div v-else>
    <p>Caricamento partita...</p>
  </div>
</template>

<script setup>
  import { useGameStore } from '@/store';
  import { useRoute, useRouter } from 'vue-router';
  import { onMounted, onUnmounted } from 'vue';
  import Navigation from '@/components/Navigation.vue';

  const gameStore = useGameStore();
  const route = useRoute();
  const router = useRouter();
  let pollingTimer = null;

  // Carica lo stato della partita quando il componente viene montato
  onMounted(async () => {
    const gameId = route.params.id;
    if (!await gameStore.loadGame(gameId)) {
      router.push('/'); // Torna al menu se la partita non esiste
    }

    // avvia aggiornamento periodico tempistiche rimanenti ed aggiornamenti patrimonio
    pollingTimer = setInterval(() => {
      console.log("fetchUpdates");
      gameStore.fetchUpdates(); 
    }, 10000);
  });

  onUnmounted(() => {
    // stop aggiornamento periodico tempistiche rimanenti ed aggiornamenti patrimonio
    clearInterval(pollingTimer);
    console.log("fetchUpdates stopped");
  });
</script>