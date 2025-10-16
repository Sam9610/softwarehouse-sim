<script setup>
  import { onMounted } from 'vue';
  import { useI18n } from 'vue-i18n';

  const { t } = useI18n();

  onMounted(() => {
    document.title = t('app.title');
  });
</script>

<template>
  <div id="app-container">
    <header>
      <img src="/images/main-logo.jpg" alt="{{ t('app.title') }}" class="main-menu-image">
    </header>
    <main>
      <router-view />
    </main>
  </div>
</template>
