<template>
  <h2>PRODUZIONE</h2>
  <div class="columns">
    <div class="column">
      <h3>Developer disponibili ({{ gameStore.availableDevelopers.length }})</h3>
      <ul>
        <li v-for="dev in gameStore.availableDevelopers" :key="dev.id" @click="selectDeveloper(dev)" :class="{ selected: selectedDeveloperId === dev.id }">
          Developer #{{ dev.id }} (Seniority: {{ dev.experience }})
        </li>
      </ul>
    </div>
    <div class="column">
      <h3>Progetti disponibili ({{ gameStore.pendingProjects.length }})</h3>
      <ul>
        <li v-for="project in gameStore.pendingProjects" :key="project.id" @click="assignProject(project)">
          Progetto #{{ project.id }} (Valore: €{{ project.value_eur }})
          <span>Complessità: {{ project.complexity }}</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
  import { useGameStore } from '@/store';
  import { ref } from 'vue';
  
  const gameStore = useGameStore();
  const selectedDeveloperId = ref(null);
  
  const selectDeveloper = (dev) => {
    selectedDeveloperId.value = dev.id;
  };
  
  const assignProject = async (project) => {
    if (!selectedDeveloperId.value) {
      alert("Seleziona prima un developer!");
      return;
    }
    try {
      // Il developer diventa "occupato" dopo l'assegnazione
      await gameStore.assign(project.id, selectedDeveloperId.value);
      selectedDeveloperId.value = null;
    } catch (error) {
      alert("Errore nell'assegnazione del progetto.");
      console.error(error);
    }
  };
</script>

<style scoped>
  li { cursor: pointer; }
  .selected { border: 2px solid #007bff; }
</style>