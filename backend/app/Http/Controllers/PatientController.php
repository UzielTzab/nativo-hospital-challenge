<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::with(['hospital', 'tutor'])->get();
        return response()->json($patients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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

        $patients = Patient::create($validatedData);

        return response()-> json([
            'message' => 'Patient created successfully',
            'patient' => $patients
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patient::with(['hospital', 'tutor'])->findOrFail($id);
        return response()->json($patient);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $patient = Patient::findOrFail($id);

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
