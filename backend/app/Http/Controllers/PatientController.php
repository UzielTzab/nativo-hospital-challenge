<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Tutor;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::with(['hospital', 'tutor'])->get();

        if ($patients->isEmpty()) {
            return response()->json(['message' => 'No hay pacientes registrados'], 404);
        }

        return response()->json($patients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'nullable|string|max:255',
            'birth_date' => 'required|date|unique:patients,birth_date',
            'sex' => 'required|in:M,F',
            'origin_city' => 'required|string',
            'admission_date' => 'required|date',
            'hospital_id' => 'required|exists:hospitals,id',
        ];

        $exists = Patient::where('name', $request->input('name'))
            ->where('paternal_surname', $request->input('paternal_surname'))
            ->where('birth_date', $request->input('birth_date'))
            ->where('hospital_id', $request->input('hospital_id'))
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Este paciente ya está registrado en el sistema',
                'error' => 'duplicate_patient'
            ], 409);
        }

        if (!$request->input('tutor_id')) {
            $rules['new_tutor_name'] = 'required|string|max:255';
            $rules['new_tutor_phone'] = 'required|string|max:20';
        } else {
            $rules['tutor_id'] = 'exists:tutors,id';
        }

        $validatedData = $request->validate($rules);

        $finalTutorId = $request->input('tutor_id');

        if (!$finalTutorId) {
            $newTutor = Tutor::create([
                'name' => $request->input('new_tutor_name'),
                'phone' => $request->input('new_tutor_phone'),
            ]);

            $finalTutorId = $newTutor->id;
        }

        $patientData = $request->except(['new_tutor_name', 'new_tutor_phone', 'tutor_id']);
        $patientData['tutor_id'] = $finalTutorId;

        $patient = Patient::create($patientData);

        return response()->json([
            'message' => 'Paciente creado exitosamente',
            'patient' => $patient
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patient::with(['hospital', 'tutor'])->findOrFail($id);
        
        if (!$patient) {
            return response()->json(['message' => 'Paciente no encontrado'], 404);
        }

        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patient = Patient::findOrFail($id);

        $exists = Patient::where('name', $request->input('name'))
            ->where('paternal_surname', $request->input('paternal_surname'))
            ->where('birth_date', $request->input('birth_date'))
            ->where('hospital_id', $request->input('hospital_id'))
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Ya existe otro paciente con estos datos',
                'error' => 'duplicate_patient'
            ], 409);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'nullable|string|max:255',
            'birth_date' => 'required|date',
            'sex' => 'required|in:M,F',
            'origin_city' => 'required|string',
            'admission_date' => 'required|date',
            'hospital_id' => 'required|exists:hospitals,id',
            'tutor_id' => 'required|exists:tutors,id',
        ]);

        $patient->update($validatedData);

        return response()->json([
            'message' => 'Paciente actualizado exitosamente',
            'patient' => $patient
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json([
            'message' => 'Paciente eliminado correctamente'
        ]);
    }
}
