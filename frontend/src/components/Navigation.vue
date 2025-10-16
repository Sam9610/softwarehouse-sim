<template>
  <nav class="game-nav" :class="`layout-${layout}`">
    <router-link :to="`/game/${gameStore.gameId}/production`" :class="linkClass">{{ $t('navigation.buttons.production') }}</router-link>
    <router-link :to="`/game/${gameStore.gameId}/sales`" :class="linkClass">{{ $t('navigation.buttons.sales') }}</router-link>
    <router-link :to="`/game/${gameStore.gameId}/hr`" :class="linkClass">{{ $t('navigation.buttons.hr') }}</router-link>
    <router-link to="/" class="exit-link" :class="[linkClass, 'exit-link']">
      <span class="long-text">{{ $t('navigation.buttons.exit') }}</span>
      <span class="short-text">{{ $t('navigation.buttons.exit_short') }}</span>
    </router-link>
  </nav>
</template>
  
<script setup lang="ts">
  import { useGameStore } from '@/store';
  import { computed } from 'vue';
  const gameStore = useGameStore();

  const props = defineProps({
    layout: {
      type: String,
      default: 'horizontal'
    }
  });

  const linkClass = computed(() => {
    return props.layout === 'vertical' ? 'menu-item' : 'nav-button';
  });
</script>
