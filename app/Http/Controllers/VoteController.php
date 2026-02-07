<?php

namespace App\Http\Controllers;



use App\Http\Controllers\Controller;
use App\Models\Vote;
use App\Models\Employee;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function castVote(Request $request, $id)
    {
        $userId = $request->user()->id;
        $employeeId = $id;

        // 1. Cek apakah karyawan ada
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        // 2. Cet apakah user SUDAH vote karyawan ini
        $existingVote = Vote::where('user_id', $userId)
                            ->where('employee_id', $employeeId)
                            ->first();

        if ($existingVote) {
            return response()->json([
                'message' => 'You have already voted for this employee.'
            ], 422);
        }

        // 3. Simpan Vote
        Vote::create([
            'user_id'     => $userId,
            'employee_id' => $employeeId,
            'status'      => 1
        ]);

        return response()->json([
            'message' => 'Vote cast successfully for ' . $employee->nama
        ], 201);
    }

    public function unvote(Request $request, $id)
{
    $userId = $request->user()->id;
    $employeeId = $id;

    // 1. Cari data vote milik user ini untuk karyawan tersebut
    $vote = Vote::where('user_id', $userId)
                ->where('employee_id', $employeeId)
                ->first();

    // 2. Jika tidak ditemukan, berarti emang belum pernah vote
    if (!$vote) {
        return response()->json([
            'message' => 'You haven\'t voted for this employee yet.'
        ], 404);
    }

    // 3. Hapus data vote (Unvote)
    $vote->delete();

    return response()->json([
        'message' => 'Unvoted successfully. You can now vote again.'
    ], 200);
}
}
