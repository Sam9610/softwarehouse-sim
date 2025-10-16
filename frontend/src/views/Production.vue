<template>
  <h2>{{ $t('pages.production') }}</h2>
  <div class="columns">
    <div class="column">
      <h3>{{ $t('pages.available_developers') }} ({{ gameStore.availableDevelopers.length }})</h3>
      <ul>
        <li v-for="dev in gameStore.availableDevelopers" :key="dev.id" @click="selectDeveloper(dev)" :class="{ selected: selectedDeveloperId === dev.id }">
          {{ $t('pages.developer') }} #{{ dev.id }} ({{ $t('pages.developer_exp') }}: {{ dev.experience }})
        </li>
      </ul>
    </div>
    <div class="column">
      <h3>{{ $t('pages.available_projects') }} ({{ gameStore.pendingProjects.length }})</h3>
      <ul>
        <li v-for="project in gameStore.pendingProjects" :key="project.id" @click="assignProject(project)">
          {{ $t('pages.project') }} #{{ project.id }} ({{ $t('pages.value_eur') }}: €{{ project.value_eur }})
          <span>Complessità: {{ project.complexity }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { useGameStore } from '@/store';
  import { ref } from 'vue';
  import { useI18n } from 'vue-i18n';
  import { useToast } from 'vue-toastification';

  const gameStore = useGameStore();
  const selectedDeveloperId = ref(null);
  const { t } = useI18n();
  const toast = useToast();

  const selectDeveloper = (dev) => {
    selectedDeveloperId.value = dev.id;
  };
  
  const assignProject = async (project) => {
    if (!selectedDeveloperId.value) {
      toast.info(t('pages.messages.select_dev_first'));
      return;
    }
    try {
      // Il developer diventa "occupato" dopo l'assegnazione
      await gameStore.assign(project.id, selectedDeveloperId.value);
      selectedDeveloperId.value = null;
    } catch (error) {
      toast.error(t('pages.messages.assign_error'));
      console.error(error);
    }
  };
</script>

<style scoped>
  li { cursor: pointer; }
  .selected { border: 2px solid #007bff; }
</style>