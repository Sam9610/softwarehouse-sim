<template>
    <h2>HR</h2>
    <div class="columns">
      <div class="column">
        <h3>Candidati sul Mercato</h3>
        <button @click="fetchMarket">Aggiorna Lista Risorse</button>
        <ul>
          <li v-for="(candidate, index) in candidates" :key="index">
            <span>{{ candidate.role }} (Exp: {{ candidate.experience }})</span>
            <span>Costo: €{{ candidate.cost }}</span>
            <button @click="hire(candidate)">Assumi</button>
          </li>
        </ul>
      </div>
      <div class="column">
          <h3>Team Attuale</h3>
          <ul>
            <li v-for="dev in gameStore.developers" :key="dev.id">Developer #{{ dev.id }}</li>
            <li v-for="sales in gameStore.sales_men" :key="sales.id">Commerciale #{{ sales.id }}</li>
          </ul>
      </div>
    </div>
  </template>
  
  <script setup>
    import { useGameStore } from '@/store';
    import backendReq from '@/api/backendReq';
    import { ref, onMounted } from 'vue';
    
    const gameStore = useGameStore();
    const candidates = ref([]);
    
    const fetchMarket = async () => {
      const response = await backendReq.getMarketCandidates();
      candidates.value = response.data;
    };
    
    const hire = async (candidate) => {
      if (gameStore.assets_eur < candidate.cost) {
        alert("Patrimonio insufficiente per l'assunzione!");
        return;
      }
      try {
        await gameStore.hire({ role: candidate.role, experience: candidate.experience, cost: candidate.cost });
        // aggiornamento con nuovi candidati
        fetchMarket();
      } catch (error) {
        alert("Errore durante l'assunzione.");
        console.error(error);
      }
    };
    
    onMounted(fetchMarket); // Carica i candidati all'apertura della schermata
  </script>