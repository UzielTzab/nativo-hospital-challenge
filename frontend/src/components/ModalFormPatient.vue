<script setup>
import { ref, watch, computed } from 'vue';
import { X, LoaderCircle, CheckCircle, AlertCircle, Search } from 'lucide-vue-next';
import PatientService from '../services/PatientService';
import TutorService from '../services/TutorService';
import HospitalService from '../services/HospitalService';

const hospitals = ref([]);
const tutors = ref([]);
const loadingData = ref(false);
const isNewTutor = ref(false);
const submitting = ref(false);
const errorMessage = ref('');
const successMessage = ref('');
const tutorSearchTerm = ref('');

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
  paternal_surname: '',
  maternal_surname: '',
  birth_date: '',
  sex: '',
  origin_city: '',
  admission_date: new Date().toISOString().split('T')[0],
  hospital_id: '',
  tutor_id: '',
  new_tutor_name: '',
  new_tutor_phone: '',
});

watch(
  () => props.open,
  async (isOpen) => {
    if (isOpen) {
      errorMessage.value = '';
      successMessage.value = '';
      await fetchCatalogs();
    }
  }
);

watch(
  () => props.patient,
  (newPatient) => {
    if (newPatient) {
      formData.value = {
        name: newPatient.name || '',
        paternal_surname: newPatient.paternal_surname || '',
        maternal_surname: newPatient.maternal_surname || '',
        birth_date: newPatient.birth_date || '',
        sex: newPatient.sex || '',
        origin_city: newPatient.origin_city || '',
        admission_date: newPatient.admission_date || '',
        hospital_id: newPatient.hospital_id || newPatient.hospital?.id || '',
        tutor_id: newPatient.tutor_id || newPatient.tutor?.id || '',
        new_tutor_name: '',
        new_tutor_phone: ''
      };
      isNewTutor.value = false;
    } else {
      resetForm();
    }
  }
);

const maxDate = computed(() => {
  return new Date().toISOString().split('T')[0];
});

const resetForm = () => {
  formData.value = {
    name: '',
    paternal_surname: '',
    maternal_surname: '',
    birth_date: '',
    sex: '',
    origin_city: '',
    admission_date: new Date().toISOString().split('T')[0],
    hospital_id: '',
    tutor_id: '',
    new_tutor_name: '',
    new_tutor_phone: '',
  };
  isNewTutor.value = false;
  errorMessage.value = '';
  successMessage.value = '';
  tutorSearchTerm.value = '';
};

const filteredTutors = computed(() => {
  if (!tutorSearchTerm.value) {
    return tutors.value;
  }
  
  const search = tutorSearchTerm.value.toLowerCase();
  return tutors.value.filter(tutor => 
    tutor.name.toLowerCase().includes(search) || 
    tutor.phone.includes(search)
  );
});

const fetchCatalogs = async () => {
  try {
    loadingData.value = true;
    const [hResponse, tResponse] = await Promise.all([
      HospitalService.getHospitals(),
      TutorService.getTutors()
    ]);
    hospitals.value = hResponse.data;
    tutors.value = tResponse.data;
  } catch (error) {
    console.error('Error cargando catálogos:', error);
    errorMessage.value = 'Error al cargar los catálogos. Por favor intenta de nuevo.';
  } finally {
    loadingData.value = false;
  }
};

const handleSubmit = async () => {
  try {
    submitting.value = true;
    errorMessage.value = '';
    successMessage.value = '';

    const payload = { ...formData.value };

    if (isNewTutor.value) {
      payload.tutor_id = null;
    } else {
      payload.new_tutor_name = null;
      payload.new_tutor_phone = null;
    }

    if (props.patient) {
      await PatientService.updatePatient(props.patient.id, payload);
      successMessage.value = 'Paciente actualizado exitosamente';
    } else {
      await PatientService.createPatient(payload);
      successMessage.value = 'Paciente registrado exitosamente';
    }

     await new Promise(resolve => setTimeout(resolve, 1500));
    emit('submit', payload);

  } catch (error) {
    console.error('Error al guardar paciente:', error);

    if (error.response?.status === 409) {
      errorMessage.value = 'Este paciente ya está registrado en el sistema';
    } else if (error.response?.status === 422) {

      const errors = error.response.data.errors;
      const errorTexts = Object.values(errors).flat();
      errorMessage.value = 'Error de validación: ' + errorTexts.join(', ');
    } else if (error.response?.data?.message) {
      errorMessage.value = '' + error.response.data.message;
    } else {
      errorMessage.value = 'Error al guardar el paciente. Por favor intenta de nuevo.';
    }
  } 
};

const closeAfterSuccess = () => {
  submitting.value = false;
  resetForm();
  emit('close');
};

defineExpose({
  closeAfterSuccess
});

const handleClose = () => {
  if (!submitting.value) {
    resetForm();
    emit('close');
  }
};
</script>

<template>
  <div v-if="open" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto mx-4">

      <header class="sticky top-0 bg-blue-500 border-b border-gray-200 p-6 flex items-center justify-between z-10">
        <h2 class="text-2xl font-bold text-white">
          {{ patient ? 'Editar Paciente' : 'Registrar Nuevo Paciente' }}
        </h2>
        <button @click="handleClose" class="text-white hover:text-gray-200 transition-colors">
          <X class="h-6 w-6" />
        </button>
      </header>

      <form @submit.prevent="handleSubmit" class="p-6 space-y-6">
        <main class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div class="space-y-2 md:col-span-2">
            <label class="text-sm font-bold text-gray-700">Nombre(s)*</label>
            <input v-model="formData.name" type="text" placeholder="Ej: María"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              required />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700">Apellido Paterno*</label>
            <input v-model="formData.paternal_surname" type="text" placeholder="Ej: Pérez"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              required />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700">Apellido Materno*</label>
            <input v-model="formData.maternal_surname" type="text" placeholder="Ej: García"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700">Fecha de Nacimiento*</label>
            <input 
              v-model="formData.birth_date" 
              type="date"
              :max="maxDate"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none disabled:bg-gray-100"
              :disabled="submitting"
              required />
            <p class="text-xs text-gray-500">No se permiten fechas futuras</p>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700">Sexo*</label>
            <select v-model="formData.sex"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white"
              required>
              <option value="">Seleccionar sexo</option>
              <option value="M">Masculino</option>
              <option value="F">Femenino</option>
            </select>
          </div>

          <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700">Lugar de Origen*</label>
            <input v-model="formData.origin_city" type="text" placeholder="Ej: Ciudad de México"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              required />
          </div>

          <div class="space-y-2">
            <label class="text-sm font-bold text-gray-700">Fecha de Admisión</label>
            <input v-model="formData.admission_date" type="date"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
              required />
          </div>

          <div class="space-y-2 md:col-span-2">
            <label class="text-sm font-bold text-gray-700">Hospital de Procedencia*</label>
            <select v-model="formData.hospital_id"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white"
              required>
              <option value="">
                {{ loadingData ? "Cargando..." : 'Seleccionar hospital' }}
              </option>
              <option v-for="hospital in hospitals" :key="hospital.id" :value="hospital.id">
                {{ hospital.name }} ({{ hospital.city }})
              </option>
            </select>
          </div>

          <div class="md:col-span-2 border p-4 rounded-lg bg-gray-100">
            <div class="flex justify-between items-center mb-4">
              <label class="text-sm font-bold text-gray-700">Datos del Tutor*</label>
              <button type="button" @click="isNewTutor = !isNewTutor; tutorSearchTerm = ''"
                class="text-sm text-blue-600 underline font-semibold"
                :disabled="submitting">
                {{ isNewTutor ? 'Seleccionar tutor existente' : '¿Es nuevo tutor?' }}
              </button>
            </div>

            <div v-if="!isNewTutor" class="space-y-3">
              <div class="relative">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
                <input 
                  v-model="tutorSearchTerm" 
                  type="text" 
                  placeholder="Buscar tutor por nombre o teléfono..."
                  class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white disabled:bg-gray-100"
                  :disabled="submitting"
                />
              </div>

              <p v-if="tutorSearchTerm" class="text-xs text-green-500">
                {{ filteredTutors.length }} tutor(es) encontrado(s)
              </p>
              <p class="text-sm font-semibold text-gray-400">Selecciona al tutor</p>
              <select 
                v-model="formData.tutor_id" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 outline-none disabled:bg-gray-100"
                :disabled="submitting"
                required>
                <option value="">
                  {{ filteredTutors.length === 0 ? 'No se encontraron tutores' : 'Seleccionar tutor registrado...' }}
                </option>
                <option v-for="t in filteredTutors" :key="t.id" :value="t.id">
                  {{ t.name }} - {{ t.phone }}
                </option>
              </select>

            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <input v-model="formData.new_tutor_name" type="text" placeholder="Nombre completo del tutor"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg disabled:bg-gray-100"
                :disabled="submitting"
                required />
              <input v-model="formData.new_tutor_phone" type="text" placeholder="Teléfono"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg disabled:bg-gray-100"
                :disabled="submitting"
                required />
            </div>
          </div>
          <div v-if="errorMessage" class="flex gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <AlertCircle class="h-5 w-5 flex-shrink-0 mt-0.5" />
            <div>
              <p class="font-semibold">Error</p>
              <p class="text-sm">{{ errorMessage }}</p>
            </div>
          </div>

          <div v-if="successMessage"
            class="flex gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            <CheckCircle class="h-5 w-5 flex-shrink-0 mt-0.5" />
            <p class="font-semibold">{{ successMessage }}</p>
          </div>
        </main>

        <footer class="flex gap-3 justify-end pt-4 border-t border-gray-200">
          <button type="button" @click="handleClose"
            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors font-medium disabled:opacity-50"
            :disabled="submitting">
            Cancelar
          </button>
          <button type="submit"
            class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors font-medium shadow-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="submitting">
            <LoaderCircle v-if="submitting" class="h-5 w-5 text-white animate-spin" />
            <span>
              {{ submitting ? (patient ? 'Actualizando...' : 'Registrando...') : (patient ? 'Actualizar Datos' :
              'Registrar Paciente') }}
            </span>
          </button>
        </footer>
      </form>
    </div>
  </div>
</template>