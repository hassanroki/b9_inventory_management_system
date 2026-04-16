<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class CategoryController extends Controller
{
    // Category List
    public function index()
    {
        try {
            $categories = Category::orderByDesc('id')->get();

            return response()->json([
                'success' => true,
                'message' => 'Category retrieve successfully!',
                'data' => $categories,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching categories!',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    // Category Store
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'status' => ['boolean'],
            ]);

            $category = Category::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully!',
                'data' => $category,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while creating category',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    // Category Show
    public function show(int $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Category fetched successfullY',
                'data' => $category,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetch show category',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    // Category Update
    public function update(Request $request, int $id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found',
                ], 404);
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'status' => ['boolean'],
            ]);

            $category->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully!',
                'data' => $category,
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating category',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    // Category Delete
    public function destroy(int $id)
    {
        try {
            $category = Category::find($id);
            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'Category not found!',
                ], 404);
            }

            $category->delete();
            return response()->json([
                'success' => true,
                'message' => 'Category deleted successfully!',
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong delete category',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
