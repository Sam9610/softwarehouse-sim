<template>
  <nav class="game-nav">
    <router-link :to="`/game/${gameStore.gameId}/production`">Produzione</router-link>
    <router-link :to="`/game/${gameStore.gameId}/sales`">Sales</router-link>
    <router-link :to="`/game/${gameStore.gameId}/hr`">HR</router-link>
    <router-link to="/" class="exit-link">Esci dalla Partita</router-link>
  </nav>
</template>
  
<script setup>
  import { useGameStore } from '@/store';
  const gameStore = useGameStore();
</script>
