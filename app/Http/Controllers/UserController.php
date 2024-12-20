<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    
    /**
     * Register a new user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,teacher,student',
            'branch_id' => 'required|exists:branches,id',
            'allow_to_create_classes' => 'nullable|boolean',
            'allow_to_create_teachers' => 'nullable|boolean',
            'allow_to_create_students' => 'nullable|boolean',
        ]);

        // Hash the password before storing
     
        $validated['password'] = bcrypt($validated['password']);
        // dd($validated);

        // Create the user
        $user = User::create($validated);
        // dd($user);

        // Return the response with the created user data
        return response()->json(['status' => 'success', 'data' => $user], 201);
    }

    /**
     * Get all users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['status' => 'success', 'data' => $users], 200);
    }

    /**
     * Show a specific user by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $user], 200);
    }

    /**
     * Get classes assigned to a teacher.
     *
     * @param int $teacherId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClassesForTeacher($teacherId)
    {
        $teacher = User::with('classes')->find($teacherId);
        if (!$teacher) {
            return response()->json(['status' => 'error', 'message' => 'Teacher not found'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $teacher->classes], 200);
    }

    /**
     * Update user details.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'nullable|in:admin,teacher,student',
            'branch_id' => 'nullable|exists:branches,id',
            'allow_to_create_classes' => 'nullable|boolean',
            'allow_to_create_teachers' => 'nullable|boolean',
            'allow_to_create_students' => 'nullable|boolean',
        ]);

        // Hash the password if it's being updated
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Update user details
        $user->update($validated);

        // Return the updated user data
        return response()->json(['status' => 'success', 'data' => $user], 200);
    }

    /**
     * Delete a user by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        // Delete the user
        $user->delete();

        // Return a success message
        return response()->json(['status' => 'success', 'message' => 'User deleted successfully'], 200);
    }
}
