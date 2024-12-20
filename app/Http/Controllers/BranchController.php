<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

class BranchController extends Controller
{
    // Create new branch
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'branch_name' => 'required|string|max:255',
                'branch_contact_number' => 'required|string|max:15',
                'branch_address' => 'required|string',
            ]);

            // Create a new branch
            $branch = Branch::create([
                'branch_name' => $request->branch_name,
                'branch_contact_number' => $request->branch_contact_number,
                'branch_address' => $request->branch_address,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Branch created successfully',
                'data' => $branch
            ], Response::HTTP_CREATED);
        } catch (QueryException $e) {
            // Handle database errors (e.g., unique constraint violation)
            return response()->json([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            // General error
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Get all branches
    public function index()
    {
        try {
            $branches = Branch::all();

            return response()->json([
                'status' => 'success',
                'data' => $branches
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while fetching branches: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Get a single branch by ID
    public function show($id)
    {
        try {
            $branch = Branch::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $branch
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while fetching branch: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Update a branch
    public function update(Request $request, $id)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'branch_name' => 'required|string|max:255',
                'branch_contact_number' => 'required|string|max:15',
                'branch_address' => 'required|string',
            ]);

            $branch = Branch::findOrFail($id);

            // Update the branch
            $branch->update([
                'branch_name' => $request->branch_name,
                'branch_contact_number' => $request->branch_contact_number,
                'branch_address' => $request->branch_address,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Branch updated successfully',
                'data' => $branch
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            // Handle database errors
            return response()->json([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while updating branch: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete a branch
    public function destroy($id)
    {
        try {
            $branch = Branch::findOrFail($id);

            // Delete the branch
            $branch->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Branch deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Branch not found'
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            // Handle database errors
            return response()->json([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error occurred while deleting branch: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
