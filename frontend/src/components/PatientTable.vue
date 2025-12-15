<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Edit, Trash2, FileDown, Search, Filter, LoaderCircle } from 'lucide-vue-next';
import ModalFormPatient from './ModalFormPatient.vue';
import PatientService from '../services/PatientService';
import { calculateAge } from '../utils/ageCalculator';
import { generatePatientPDF } from '../utils/pdfGenerator';

const props = defineProps({
  showModal: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close-modal', 'edit']);
const patients = ref([]);
const loading = ref(true);
const isDeleting = ref(false);
const searchTerm = ref('');
const filterSexo = ref('all');
const deletePatientId = ref(null);
const showFormModal = ref(false);
const editingPatient = ref(null);
const modalRef = ref(null);

watch(() => props.showModal, (newValue) => {
  showFormModal.value = newValue;
});

const filteredPatients = computed(() => {
  return patients.value.filter((patient) => {
    const searchLower = searchTerm.value.toLowerCase();
    const matchesSearch =
      patient.name.toLowerCase().includes(searchLower) ||
      patient.paternal_surname.toLowerCase().includes(searchLower) ||
      patient.maternal_surname.toLowerCase().includes(searchLower) ||
      patient.hospital.name.toLowerCase().includes(searchLower) ||
      patient.origin_city.toLowerCase().includes(searchLower);

    const matchesSexo = filterSexo.value === 'all' || patient.sex === filterSexo.value;

    return matchesSearch && matchesSexo;
  });
});

const calculateAgeForPatient = (birthDate) => {
  return calculateAge(birthDate);
};

const handleDownloadPatientData = (patient) => {
  try {
    generatePatientPDF(patient);
  } catch (error) {
    console.error('Error al generar PDF:', error);
    alert('Error al generar el PDF. Por favor intenta de nuevo.');
  }
};

const fetchPatients = async () => {
  try {
    const response = await PatientService.getPatients();
    patients.value = response.data;
    console.log('Pacientes cargados:', patients.value.length);
  } catch (error) {
    console.error('Error al cargar pacientes:', error);
  } finally {
    loading.value = false;
  }
};

const handleCloseModal = () => {
  showFormModal.value = false;
  editingPatient.value = null;
  emit('close-modal');
};

const handleFormSubmit = async (formData) => {
  console.log('Guardando paciente...', formData);

  try {
    console.log('Recargando lista de pacientes...');
    await fetchPatients();
    console.log('Lista actualizada');

    await new Promise(resolve => setTimeout(resolve, 500));

    if (modalRef.value) {
      modalRef.value.closeAfterSuccess();
    }

    handleCloseModal();

  } catch (error) {
    console.error('Error al actualizar lista:', error);
  }
};

const handleEditPatient = (patient) => {
  editingPatient.value = patient;
  showFormModal.value = true;
};

const handleDelete = async (patientId) => {
  try {
    isDeleting.value = true;
    await PatientService.deletePatient(patientId);
    await fetchPatients();
    isDeleting.value = false;
    deletePatientId.value = null;
  } catch (error) {
    console.error('Error al eliminar paciente:', error);
  } finally {
    isDeleting.value = false;
  }
};

onMounted(async () => {
  await fetchPatients();
});
</script>

<template>
  <div>
    <ModalFormPatient ref="modalRef" :open="showFormModal" :patient="editingPatient" @close="handleCloseModal"
      @submit="handleFormSubmit" />
    <div class="p-6 bg-white border border-gray-200 rounded-lg shadow">
      <div class="space-y-6">
        <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between">
          <div>
            <h2 class="text-2xl font-semibold text-gray-900">Registro de Pacientes</h2>
          </div>

          <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <div class="relative flex-1 sm:w-64">
              <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" />
              <input v-model="searchTerm" type="text" placeholder="Buscar pacientes..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
            </div>

            <div class="relative w-full sm:w-40">
              <Filter
                class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400 pointer-events-none" />
              <select v-model="filterSexo"
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent appearance-none bg-white cursor-pointer">
                <option value="all">Todos</option>
                <option value="M">Masculino</option>
                <option value="F">Femenino</option>
              </select>
            </div>
          </div>
        </div>

        <div v-if="loading" class="flex flex-col items-center justify-center py-12">
          <LoaderCircle class="h-8 w-8 text-blue-600 animate-spin mb-4" />
          <p class="text-gray-600">Cargando pacientes...</p>
        </div>

        <div v-else-if="filteredPatients.length === 0" class="text-center py-12">
          <p class="text-gray-600 text-lg">No hay pacientes registrados</p>
          <p class="text-sm text-gray-500 mt-2">Haz clic en "Nuevo Paciente" para agregar uno</p>
        </div>

        <div v-else class="overflow-x-auto -mx-6">
          <div class="inline-block min-w-full align-middle px-6">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr class="bg-blue-100">
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Paciente
                  </th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Edad
                  </th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Sexo
                  </th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Origen
                  </th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Hospital
                  </th>
                  <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Tutor
                  </th>
                  <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">
                    Acciones
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr v-for="patient in filteredPatients" :key="patient.id" class="hover:bg-gray-50 transition-colors">
                  <td class="px-4 py-4 whitespace-nowrap">
                    <div class="font-medium text-gray-900">{{ patient.name }}</div>
                    <div class="text-sm text-gray-500">{{ patient.paternal_surname }} {{ patient.maternal_surname }}</div>
                  </td>
                  <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                    {{ calculateAgeForPatient(patient.birth_date) }} años
                  </td>
                  <td class="px-4 py-4 whitespace-nowrap">
                    <span
                      class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-xl bg-blue-100 text-blue-900">
                      {{ patient.sex === 'M' ? 'Masculino' : 'Femenino' }}
                    </span>
                  </td>
                  <td class="px-4 py-4 text-sm text-gray-600">{{ patient.origin_city }}</td>
                  <td class="px-4 py-4 text-sm text-gray-600">
                    <div class="font-medium">{{ patient.hospital?.name}}</div>
                    <div class="text-xs text-gray-400">{{ patient.hospital?.city }}</div>
                  </td>
                  <td class="px-4 py-4">
                    <div class="text-sm">
                      <div class="text-gray-500 font-semibold">{{ patient.tutor.name }}</div>
                      <div class="text-gray-600">{{ patient.tutor.phone }}</div>
                    </div>
                  </td>
                  <td class="px-4 py-4 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center gap-2">
                      <button @click="handleEditPatient(patient)"
                        class="h-8 w-8 inline-flex items-center justify-center text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                        title="Editar paciente">
                        <Edit class="h-4 w-4 text-blue-600" />
                      </button>
                      <button @click="handleDownloadPatientData(patient)"
                        class="h-8 w-8 inline-flex items-center justify-center text-green-600 hover:bg-blue-100 rounded-lg transition-colors"
                        title="Descargar ficha en PDF">
                        <FileDown class="h-4 w-4 text-blue-600" />
                      </button>
                      <button @click="deletePatientId = patient.id"
                        class="h-8 w-8 inline-flex bg-red-100 items-center justify-center text-red-600 hover:bg-red-200 rounded-lg transition-colors"
                        title="Eliminar paciente">
                        <Trash2 class="h-4 w-4 text-red-600" />
                      </button>

                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div v-if="deletePatientId" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm mx-4">
        <header>
          <h3 class="text-lg font-bold text-gray-900 mb-2">¿Eliminar paciente?</h3>
        </header>
        <main>
          <p class="text-sm text-gray-600 mb-6">
            Esta acción no se puede deshacer. El registro del paciente será eliminado permanentemente del sistema.
          </p>
        </main>
        <footer class="flex gap-3 justify-end">
          <button @click="deletePatientId = null"
            class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
            Cancelar
          </button>
          <button @click="handleDelete(deletePatientId)"
            class="px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
            :disabled="isDeleting">
            <LoaderCircle v-if="isDeleting" class="h-5 w-5 text-white animate-spin" />
            <span>{{ isDeleting ? 'Eliminando...' : 'Eliminar' }}</span>
          </button>
        </footer>
      </div>
    </div>
  </div>
</template>