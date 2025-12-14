import axios from 'axios';
const API_URL = 'http://localhost:8001/api';

export default
{
    getTutors()
    {
        return axios.get(`${API_URL}/tutors`);
    }
}