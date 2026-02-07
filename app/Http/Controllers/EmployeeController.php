<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{

    // 1. GET ALL DATA
    public function index(Request $request)
    {

        // if ($request->user()->role !== 'admin') {
        //     return response()->json(['message' => 'Unauthorized. Admin only!'], 403);
        // }
        // Menggunakan 'with' agar data komentar ikut terbawa (Eager Loading)
        $employees = Employee::with('comments.user:id,name')->get();
        return response()->json([
            'success' => true,
            'data'    => $employees
        ], 200);
    }

    // 2. ADD EMPLOYEE
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'       => 'required',
            'masa_kerja' => 'required|integer',
            'jobdesk'    => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $employee = Employee::create($request->all());

        return response()->json([
            'message' => 'Employee added successfully',
            'data'    => $employee
        ], 201);
    }

    // 3. EDIT EMPLOYEE
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['message' => 'Employee not found'], 404);

        $validator = Validator::make($request->all(), [
            'nama'       => 'required',
            'masa_kerja' => 'required|integer',
            'jobdesk'    => 'required',
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $employee->update($request->all());

        return response()->json([
            'message' => 'Employee updated successfully',
            'data'    => $employee
        ], 200);
    }

    // 4. DELETE EMPLOYEE
    public function destroy($id)
    {
        $employee = Employee::find($id);
        if (!$employee) return response()->json(['message' => 'Employee not found'], 404);

        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully'], 200);
    }

    public function countVotes()
{
    // Mengambil semua employee dan menghitung jumlah vote-nya
    $employees = Employee::withCount('votes')->get();

    // Transformasi data agar sesuai dengan format response yang kamu minta
    $formattedData = $employees->map(function ($employee) {
        return [
            'nama'        => $employee->nama,
            'id'          => $employee->id,
            'total votes' => $employee->votes_count,
        ];
    });

    return response()->json([
        'employees' => $formattedData
    ], 200);
}

}
