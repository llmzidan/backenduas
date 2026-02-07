<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_employee' => 'required|exists:employees,id',
            'comment'     => 'required'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 422);

        $comment = Comment::create([
            'user_id'     => $request->user()->id,
            'employee_id' => $request->id_employee,
            'comment'     => $request->comment
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'data'    => $comment
        ], 201);
    }
}
