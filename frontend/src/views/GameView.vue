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
  import { onMounted } from 'vue';
  import Navigation from '@/components/Navigation.vue';

  const gameStore = useGameStore();
  const route = useRoute();
  const router = useRouter();

  // Carica lo stato della partita quando il componente viene montato
  onMounted(async () => {
    const gameId = route.params.id;
    if (!await gameStore.loadGame(gameId)) {
      router.push('/'); // Torna al menu se la partita non esiste
    }
  });
</script>