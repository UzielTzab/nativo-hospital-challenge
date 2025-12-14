<script setup>
import { ref } from 'vue';
import Header from './components/Header.vue'
import PatientTable from './components/PatientTable.vue';
import { Plus } from 'lucide-vue-next';

const showFormModal = ref(false);

const handleOpenFormModal = () => {
  showFormModal.value = true;
};

</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <Header @open-form-modal="handleOpenFormModal" />
    <main class="container mx-auto px-4 py-8 max-w-7xl">

      <div class="flex items-center justify-between mb-6">
        <h1 class="text-lg  text-gray-500 mb-2">Sistema de Registro de Pacientes</h1>

        <button @click="handleOpenFormModal"
          class="gap-2 bg-blue-500 text-white hover:bg-blue-600 px-4 py-2 rounded-lg font-medium flex items-center transition-colors">
          <Plus class="h-5 w-5 text-white" />
          Nuevo Paciente
        </button>
      </div>

      <PatientTable :showModal="showFormModal" @close-modal="showFormModal = false" />
    </main>
  </div>
</template>