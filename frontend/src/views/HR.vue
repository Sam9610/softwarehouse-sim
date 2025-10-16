<template>
    <h2>{{ $t('pages.hr') }}</h2>
    <div class="columns">
      <div class="column">
        <h3>{{ $t('pages.available_employees') }}</h3>
        <button @click="fetchMarket">{{ $t('pages.buttons.reload_employees') }}</button>
        <ul>
          <li v-for="(candidate, index) in candidates" :key="index">
            <span>{{ $t('pages.'+candidate.role) }} (Exp: {{ candidate.experience }})</span>
            <span>{{ $t('pages.hiring_cost') }}: €{{ candidate.cost }}</span>
            <button @click="hire(candidate)">{{ $t('pages.buttons.hire') }}</button>
          </li>
        </ul>
      </div>
      <div class="column">
          <h3>{{ $t('pages.team') }}</h3>
          <ul>
            <li v-for="employee in gameStore.developers" :key="employee.id">
              <div>
                <strong>{{ $t('pages.developer') }} #{{ employee.id }}</strong>
              </div>
              
              <div class="assignment-details">
                <template v-if="getAssignedProject(employee.id)">
                  <span class="project-info">
                    {{ $t('pages.developing') }} <strong>{{ $t('pages.project') }} #{{ getAssignedProject(employee.id).id }}</strong>
                    <br>
                    <small>{{ $t('pages.remaining_time') }}: {{ getAssignedProject(employee.id).remaining_time }}s</small>
                  </span>
                </template>
                <template v-else>
                  <span class="status-available">
                    {{ $t('pages.status_available') }}
                  </span>
                </template>
              </div>
            </li>

            <li v-for="employee in gameStore.sales_men" :key="employee.id">
              <div>
                <strong>{{ $t('pages.sales_man') }} #{{ employee.id }}</strong>
              </div>
              
              <div class="assignment-details">
                <template v-if="getAssignedProject(employee.id)">
                  <span class="project-info">
                    {{ $t('pages.designing') }} <strong>{{ $t('pages.project') }} #{{ getAssignedProject(employee.id).id }}</strong>
                    <br>
                    <small>{{ $t('pages.remaining_time') }}: {{ getAssignedProject(employee.id).remaining_time }}s</small>
                  </span>
                </template>
                <template v-else>
                  <span class="status-available">
                    {{ $t('pages.status_available') }}
                  </span>
                </template>
              </div>
            </li>
          </ul>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
    import { useGameStore } from '@/store';
    import backendReq from '@/api/backendReq';
    import { ref, onMounted } from 'vue';
    import { useI18n } from 'vue-i18n';
    import { useToast } from 'vue-toastification';

    const gameStore = useGameStore();
    const candidates = ref([]);
    const { t } = useI18n();
    const toast = useToast();

    const fetchMarket = async () => {
      const response = await backendReq.getMarketCandidates();
      candidates.value = response.data;
    };
    
    const hire = async (candidate) => {
      if (gameStore.assets_eur < candidate.cost) {
        toast.error(t('pages.messages.assets_too_low'));
        return;
      }
      try {
        await gameStore.hire({ role: candidate.role, experience: candidate.experience, cost: candidate.cost });
        // aggiornamento con nuovi candidati
        fetchMarket();
      } catch (error) {
        toast.error(t('pages.messages.hiring_error'));
        console.error(error);
      }
    };

    const getAssignedProject = (employeeId) => {
      return gameStore.projects.find(
        project => project.employee_id === employeeId && ['in_progress', 'in_design'].includes(project.status)
      );
    };
    
    onMounted(fetchMarket); // Carica i candidati all'apertura della schermata
  </script>