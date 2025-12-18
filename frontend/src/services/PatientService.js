import axios from 'axios';

const API_URL = 'http://localhost:8001/api';

export default {
    
    getPatients() {
        return axios.get(`${API_URL}/patients`);
    },

    getPatient(id) {
        return axios.get(`${API_URL}/patients/${id}`);
    },

    createPatient(data) {
        return axios.post(`${API_URL}/patients`, data);
    },

    updatePatient(id, data) {
        return axios.put(`${API_URL}/patients/${id}`, data);
    },

    deletePatient(id) {
        return axios.delete(`${API_URL}/patients/${id}`);
    },
};