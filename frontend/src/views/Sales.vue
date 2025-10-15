<template>
  <h2>{{ $t('pages.sales') }}</h2>
  <div class="columns">
    <div class="column">
      <h3>{{ $t('pages.available_salesmen') }} ({{ gameStore.availableSales.length }})</h3>
      <ul>
        <li v-for="salesperson in gameStore.availableSales" :key="salesperson.id">
          <span>{{ $t('pages.sales_man') }} #{{ salesperson.id }} ({{ $t('pages.sales_man_exp') }}: {{ salesperson.experience }})</span>
          <button @click="generateNewProject(salesperson)">{{ $t('pages.buttons.design') }}</button>
        </li>
      </ul>
    </div>
    <div class="column">
      <h3>{{ $t('pages.in_design_projects') }}</h3>
      <ul>
        <li v-for="project in gameStore.designingProjects" :key="project.id">
          <span>{{ $t('pages.project') }} #{{ project.id }} ({{ $t('pages.value_eur') }}: €{{ project.value_eur }})</span>
          <span class="float-right">{{ $t('pages.remaining_time') }}: {{ project.remaining_time }}s</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
  import { useGameStore } from '@/store';
  import { useI18n } from 'vue-i18n';
  
  const gameStore = useGameStore();
  const { t } = useI18n();
  
  const generateNewProject = async (salesperson) => {
    try {
      // I progetti generati entrano in pending
      console.log(salesperson)
      await gameStore.generate(salesperson.id);
    } catch (error) {
      alert(t('pages.messages.design_error'));
      console.error(error);
    }
  };
</script>