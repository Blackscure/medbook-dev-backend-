<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Service;

class PatientController extends Controller
{
    /**
     * Get a list of all patients.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $patients = Patient::with('gender', 'services')->get();
            return response()->json($patients);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching patients: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get a specific patient by ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $patient = Patient::with('gender', 'services')->find($id);
            if ($patient) {
                return response()->json($patient);
            } else {
                return response()->json(['error' => 'Patient not found.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching the patient.'], 500);
        }
    }

    /**
     * Create a new patient.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'date_of_birth' => 'required|date',
                'gender_id' => 'required|exists:tbl_gender,id',
                'type_of_service' => 'required|exists:tbl_service,id',
                'general_comments' => 'nullable|string',
            ]);

            $patient = new Patient([
                'name' => $request->input('name'),
                'date_of_birth' => $request->input('date_of_birth'),
                'gender_id' => $request->input('gender_id'),
                'type_of_service' => $request->input('type_of_service'),
                'general_comments' => $request->input('general_comments'),
            ]);

            $patient->save();

            // Attach the selected service
            $service = Service::find($request->input('type_of_service'));
            $patient->services()->attach($service);

            return response()->json(['message' => 'Patient created successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while creating the patient: ' . $e->getMessage()], 500);
        }
    }
    /**
     * Update an existing patient.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $patient = Patient::find($id);
            if (!$patient) {
                return response()->json(['error' => 'Patient not found.'], 404);
            }

            $request->validate([
                'name' => 'required|string',
                'date_of_birth' => 'required|date',
                'gender_id' => 'required|exists:tbl_gender,id',
                'type_of_service' => 'required|exists:tbl_service,id',
                'general_comments' => 'nullable|string',
            ]);

            $patient->update([
                'name' => $request->input('name'),
                'date_of_birth' => $request->input('date_of_birth'),
                'gender_id' => $request->input('gender_id'),
                'type_of_service' => $request->input('type_of_service'),
                'general_comments' => $request->input('general_comments'),
            ]);

            // Sync the selected service
            $service = Service::find($request->input('type_of_service'));
            $patient->services()->sync([$service->id]);

            return response()->json(['message' => 'Patient updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the patient.'], 500);
        }
    }

    /**
     * Delete a patient.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $patient = Patient::find($id);
            if (!$patient) {
                return response()->json(['error' => 'Patient not found.'], 404);
            }

            $patient->delete();

            return response()->json(['message' => 'Patient deleted successfully.'], 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the patient.'], 500);
        }
    }
}
