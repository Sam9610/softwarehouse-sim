<template>
  <nav class="game-nav">
    <router-link :to="`/game/${gameStore.gameId}/production`">{{ $t('navigation.buttons.production') }}</router-link>
    <router-link :to="`/game/${gameStore.gameId}/sales`">{{ $t('navigation.buttons.sales') }}</router-link>
    <router-link :to="`/game/${gameStore.gameId}/hr`">{{ $t('navigation.buttons.hr') }}</router-link>
    <router-link to="/" class="exit-link">{{ $t('navigation.buttons.exit') }}</router-link>
  </nav>
</template>
  
<script setup>
  import { useGameStore } from '@/store';
  const gameStore = useGameStore();
</script>
