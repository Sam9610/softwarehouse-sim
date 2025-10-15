<template>
  <h2>COMMERCIALI</h2>
  <div class="columns">
    <div class="column">
      <h3>Commerciali Disponibili ({{ gameStore.availableSales.length }})</h3>
      <ul>
        <li v-for="salesperson in gameStore.availableSales" :key="salesperson.id">
          <span>Commerciale #{{ salesperson.id }} (Esperienza: {{ salesperson.experience }})</span>
          <button @click="generateNewProject(salesperson)">Genera Progetto</button>
        </li>
      </ul>
    </div>
    <div class="column">
      <h3>Progetti in generazione</h3>
      <ul>
        <li v-for="project in gameStore.designingProjects" :key="project.id">
          <span>Progetto #{{ project.id }} (Valore: €{{ project.value_eur }})</span>
          <span class="float-right">Tempo rimanente: {{ project.remaining_time }}s</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
  import { useGameStore } from '@/store';
  
  const gameStore = useGameStore();
  
  const generateNewProject = async (salesperson) => {
    try {
      // I progetti generati entrano in pending
      console.log(salesperson)
      await gameStore.generate(salesperson.id);
    } catch (error) {
      alert("Errore nella generazione del progetto.");
      console.error(error);
    }
  };
</script>