<script setup>
import { Hospital, Plus } from 'lucide-vue-next';

function onNewPatient() {
    // Lógica para manejar la creación de un nuevo paciente
    alert('Nuevo paciente creado!');
}

</script>

<template>
    <header class="border-b border-blue-400 bg-blue-500 sticky top-0 z-50 ">
      <div class="container mx-auto px-4 py-4 max-w-7xl">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <div class="p-2 bg-white/20 rounded-lg">
              <Hospital class="h-6 w-6 text-white" fill="currentColor" />
            </div>
            <div>
              <h1 class="text-xl font-semibold text-white">Hospital Infantil</h1>
              <p class="text-sm text-white/80">Sistema de Registro de Pacientes</p>
            </div>
          </div>

          <Button @click="onNewPatient" size="lg" class="gap-2 bg-white text-blue-500 hover:bg-white/90 px-4 py-2 rounded-lg  font-medium flex items-center">
            <Plus class="h-5 w-5" />
            Nuevo Paciente
          </Button>
        </div>
      </div>
    </header>
</template>

<style scoped>

</style>