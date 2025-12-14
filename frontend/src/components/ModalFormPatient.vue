<script setup>
import { ref, watch } from 'vue';
import { X } from 'lucide-vue-next';

const props = defineProps({
  open: {
    type: Boolean,
    required: true,
  },
  patient: {
    type: Object,
    default: null,
  },
});

const emit = defineEmits(['submit', 'close']);

const formData = ref({
  name: '',
  birth_date: '',
  sex: '',
  origin_city: '',
  hospital_name: '',
  tutor_name: '',
  tutor_phone: '',
});

const SEXOS = ['M', 'F'];

watch(
  () => props.patient,
  (newPatient) => {
    if (newPatient) {
      formData.value = {
        name: newPatient.name || '',
        birth_date: newPatient.birth_date || '',
        sex: newPatient.sex || '',
        origin_city: newPatient.origin_city || '',
        hospital_id: newPatient.hospital?.id || '',
        tutor_id: newPatient.tutor?.id || '',
      };
    } else {
      resetForm();
    }
  }
);

const resetForm = () => {
  formData.value = {
    name: '',
    birth_date: '',
    sex: '',
    origin_city: '',
    hospital_id: '',
    tutor_id: '',
  };
};

const fetchRegisterPatient=()=>
{
    alert("Paciente registrado/actualizado exitosamente!");
}

const handleSubmit = () => {
  if (props.patient) {
    emit('submit', { ...formData.value, id: props.patient.id });
  } else {
    emit('submit', formData.value);
  }
};

const handleClose = () => {
  resetForm();
  emit('close');
};
</script>

<template>
  <div v-if="open" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto mx-4">
      <header class="sticky top-0 bg-blue-500 border-b border-gray-200 p-6 flex items-center justify-between">
        <h2 class="text-2xl font-semibold text-white">
          {{ patient ? 'Editar Paciente' : 'Registrar Nuevo Paciente' }}
        </h2>
        <button
          @click="handleClose"
          class="text-white hover:text-white-600 transition-colors"
        >
          <X class="h-6 w-6 "/>
        </button>
      </header>
      <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
        <main class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Nombre Completo del Paciente</label>
            <input
              v-model="formData.name"
              type="text"
              placeholder="Ej: María Pérez García"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              required
            />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
            <input
              v-model="formData.birth_date"
              type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              required
            />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Sexo</label>
            <select
              v-model="formData.sex"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
              required
            >
              <option value="">Seleccionar sexo</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Lugar de Origen</label>
            <input
              v-model="formData.origin_city"
              type="text"
              placeholder="Ej: Ciudad de México"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              required
            />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Hospital</label>
            <select
              v-model="formData.hospital_name"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
              required
            >
              <option value="">Seleccionar hospital</option>
              <option value="Agustín Oran">Agustín Oran</option>
              <option value="Mérida hospital">Mérida hospital</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Tutor</label>
            <input
              v-model="formData.tutor_name"
              type="text"
              placeholder="Nombre del tutor"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              required
            />
          </div>
          <div class="space-y-2">
            <label class="text-sm font-medium text-gray-700">Teléfono del Tutor</label>
            <input
              v-model="formData.tutor_phone"
              type="number"
              placeholder="Teléfono del tutor"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              required
            />
          </div>
        </main>

        <footer class="flex gap-3 justify-end pt-4 border-t border-gray-200">
          <button
            type="button"
            @click="handleClose"
            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors font-medium"
          >
            Cancelar
          </button>
          <button
            @click="fetchRegisterPatient"
            type="submit"
            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors font-medium flex items-center gap-2"
          >
            {{ patient ? 'Actualizar' : 'Registrar' }} Paciente
          </button>
        </footer>
      </form>
    </div>
  </div>
</template>