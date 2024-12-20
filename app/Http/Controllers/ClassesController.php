<?php

namespace App\Http\Controllers;

use App\Models\ClassesModel;
use App\Models\User;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    // CREATE: Add a new class
    public function store(Request $request)
{
    $validated = $request->validate([
        'class_name' => 'required|string|max:255',
        'class_short_description' => 'required|string|max:255',
        'total_strength' => 'required|integer',
        'created_by' => 'required|exists:users,id', 
        'date' => 'required|date',
        'status' => 'required|in:active,inactive,completed',
        'active_days' => 'nullable|array',
        'active_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
        'teacher_id' => 'required|exists:users,id',  // Single teacher for class
    ]);

    // Check if the provided teacher_id belongs to a user with the "Teacher" role
    $teacher = User::where('id', $validated['teacher_id'])->where('role', 'Teacher')->first();

    if (!$teacher) {
        return response()->json([
            'status' => 'error',
            'message' => 'The assigned user must have the role of "Teacher".',
        ], 400);
    }

    // Check if the teacher is already assigned to another class (if needed)
    $existingTeacher = ClassesModel::where('teacher_id', $validated['teacher_id'])->first();
    if ($existingTeacher) {
        return response()->json([
            'status' => 'error',
            'message' => 'This teacher is already assigned to another class.',
        ], 400);
    }

    // Create the class and assign the teacher_id
    $class = ClassesModel::create([
        'class_name' => $validated['class_name'],
        'class_short_description' => $validated['class_short_description'],
        'total_strength' => $validated['total_strength'],
        'created_by' => $validated['created_by'],
        'date' => $validated['date'],
        'status' => $validated['status'],
        'active_days' => $validated['active_days'] ?? [],
        'teacher_id' => $validated['teacher_id'],  // Associate the single teacher
    ]);

    return response()->json(['status' => 'success', 'data' => $class], 201);
}

    

    // READ: Get teacher for a class
    public function getTeacherForClass($classId)
    {
        $class = ClassesModel::find($classId);

        if (!$class) {
            return response()->json(['status' => 'error', 'message' => 'Class not found'], 404);
        }

        // Ensure the teacher relationship is loaded before returning it
        $class->load('teacher');

        return response()->json(['status' => 'success', 'data' => $class->teacher], 200);
    }

    // UPDATE: Assign or update teacher for a class
    public function update(Request $request, $id)
    {
        $class = ClassesModel::find($id);

        if (!$class) {
            return response()->json(['status' => 'error', 'message' => 'Class not found'], 404);
        }

        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',  // Single teacher for class
        ]);

        // Check if the teacher is already assigned to another class (if needed)
        $existingTeacher = ClassesModel::where('teacher_id', $validated['teacher_id'])->first();
        if ($existingTeacher && $existingTeacher->id !== $class->id) {
            return response()->json(['status' => 'error', 'message' => 'This teacher is already assigned to another class'], 400);
        }

        // Update the teacher_id for the class
        $class->update([
            'teacher_id' => $validated['teacher_id'],
        ]);

        return response()->json(['status' => 'success', 'data' => $class], 200);
    }

    // DELETE: Remove a teacher from a class
    public function removeTeacherFromClass(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:classes,id',
            'teacher_id' => 'required|exists:users,id',
        ]);
    
        $class = ClassesModel::find($validated['class_id']);
    
        if (!$class) {
            return response()->json(['status' => 'error', 'message' => 'Class not found'], 404);
        }
    
        // Check if the class has the specified teacher
        if ($class->teacher_id != $validated['teacher_id']) {
            return response()->json(['status' => 'error', 'message' => 'Teacher does not belong to this class'], 400);
        }
    
        // Remove teacher by setting teacher_id to NULL or a default value
        $class->update(['teacher_id' => null]); // Or use 0 if not nullable
    
        return response()->json(['status' => 'success', 'message' => 'Teacher removed from class successfully'], 200);
    }
    // Assign Teacher to a class


    public function assignTeacherToClass(Request $request, $classId)

    {
        // Validate the incoming request
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',  // Ensure the teacher exists
        ]);
    
        // Find the class by ID
        $class = ClassesModel::find($classId);
    
        if (!$class) {
            return response()->json(['status' => 'error', 'message' => 'Class not found'], 404);
        }
    
        // Check if the teacher is already assigned to another class
        $existingTeacher = ClassesModel::where('teacher_id', $validated['teacher_id'])->first();
        if ($existingTeacher) {
            return response()->json(['status' => 'error', 'message' => 'This teacher is already assigned to another class'], 400);
        }
    
        // Assign the teacher to the class
        $class->teacher_id = $validated['teacher_id'];
        $class->save();
    
        return response()->json(['status' => 'success', 'data' => $class], 200);
    }

    public function index()
    {
        $classes = ClassesModel::with('teacher')->get();
    
        return response()->json([
            'status' => 'success',
            'data' => $classes,
        ], 200);
    }
    public function destroy($id)
{
    // Find the class by ID
    $class = ClassesModel::find($id);

    if (!$class) {
        return response()->json(['status' => 'error', 'message' => 'Class not found'], 404);
    }

    // Delete the class
    $class->delete();

    return response()->json(['status' => 'success', 'message' => 'Class deleted successfully'], 200);
}

    
}
