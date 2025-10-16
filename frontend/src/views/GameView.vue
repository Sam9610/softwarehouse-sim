<template>
  <button @click="isSidebarOpen = !isSidebarOpen" class="hamburger-menu">
    ☰
  </button>

  <aside class="sidebar" :class="{ 'is-open': isSidebarOpen }">
    <Navigation layout="vertical" />
  </aside>

  <div v-if="gameStore.gameId">
    <div class="game-header">
      <h3>{{ $t('game.assets_eur') }}: €{{ gameStore.assets_eur.toFixed(2) }}</h3>
    </div>
    <div class="screen-container">
      <router-view />
    </div>

    <Navigation v-if="!isSidebarOpen" />
  </div>
  <div v-else>
    <p>{{ $t('menu.loading_game') }}</p>
  </div>
</template>

<script setup>
  import { useGameStore } from '@/store';
  import { useRoute, useRouter } from 'vue-router';
  import { onMounted, onUnmounted, ref } from 'vue';
  import Navigation from '@/components/Navigation.vue';

  const gameStore = useGameStore();
  const route = useRoute();
  const router = useRouter();
  const isSidebarOpen = ref(false);
  let pollingTimer = null;

  // Carica lo stato della partita quando il componente viene montato
  onMounted(async () => {
    const gameId = route.params.id;
    if (!await gameStore.loadGame(gameId)) {
      router.push('/'); // Torna al menu se la partita non esiste
    }

    // avvia aggiornamento periodico tempistiche rimanenti ed aggiornamenti patrimonio
    pollingTimer = setInterval(async () => {
      const gameStatus = await gameStore.fetchUpdates();
      // console.log("GameView -> onmounted, gameStatus:", gameStatus);
      // check bancarotta
      if (gameStatus === 'bankruptcy') {
        clearInterval(pollingTimer);
        // se bancarotta ferma la partita e reindirizza a menu
        router.push({ name: 'MainMenu', query: { status: 'lost' } });
      }
    }, 10000);
  });

  onUnmounted(() => {
    // stop aggiornamento periodico tempistiche rimanenti ed aggiornamenti patrimonio
    clearInterval(pollingTimer);
    console.log("fetchUpdates stopped");
  });
</script>