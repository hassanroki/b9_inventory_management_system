<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class StockController extends Controller
{
    // Stock Movement List
    public function index()
    {
        try {
            $stockMovements = StockMovement::with('product.category')->orderByDesc('id')
                ->get();
            return response()->json([
                'success' => true,
                'message' => 'Stock movements list fetched successfully!',
                'data' => $stockMovements,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching stock movements',
            ], 500);
        }
    }

    // Stock IN
    public function stockIn(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => ['required', 'integer', 'exists:products,id'],
                'quantity' => ['required', 'integer', 'min:1'],
                'note' => ['nullable', 'string'],
            ]);

            DB::beginTransaction();
            // Stock Store
            $stockMovement = StockMovement::create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'type' => 'IN',
                'note' => $validated['note'] ?? null,
            ]);

            // Product Table stock_qty Update
            $product = Product::findOrFail($validated['product_id']);

            // Option: 1
            // $old_stock = $product->stock_qty;
            // $updated_stock = $old_stock + $validated['quantity'];
            // $product->stock_qty = $updated_stock;
            // $product->save();

            // Option: 2
            $product->stock_qty += $validated['quantity'];
            $product->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock IN recorded successfully!',
                'data' => $stockMovement,
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation Error!',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while recording Stock IN',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    // Stock Adjustment
    public function stockAdjustment(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => ['required', 'integer', 'exists:products,id'],
                'quantity' => ['required', 'integer'],
                'type' => ['required', 'string', 'in:IN,OUT'],
                'note' => ['nullable', 'string'],
                'invoice_id' => ['nullable', 'integer', 'exists:invoices,id'],
            ]);

            DB::beginTransaction();

            // Stock Store
            $stockMovement = StockMovement::create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'type' => $validated['type'],
                'note' => $validated['note'] ?? null,
                'invoice_id' => $validated['invoice_id'] ?? null,
            ]);

            // Update product stock quantity based on adjustment type
            $product = Product::findOrFail($validated['product_id']);

            if ($validated['type'] == 'IN') {
                $product->stock_qty += $validated['quantity'];
            } else {
                if ($product->stock_qty < $validated['quantity']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock for OUT adjustment',
                    ], 400);
                }
                $product->stock_qty -= $validated['quantity'];
            }
            $product->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock adjustment successful',
                'data' => $stockMovement,
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation Failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while recording stock adjustment',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
