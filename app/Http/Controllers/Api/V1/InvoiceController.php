<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\StockMovement;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class InvoiceController extends Controller
{
    // Invoice
    public function index()
    {
        try {
            $invoices = Invoice::with(['items.product.category', 'customer'])
                ->orderByDesc('id')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Invoices list fetched successfully',
                'data' => $invoices,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching invoices',
            ], 500);
        }
    }

    // Invoice Store
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
                'invoice_no' => ['nullable', 'string', 'max:255', 'unique:invoices,invoice_no'],
                'invoice_date' => ['required', 'date'],
                'items' => ['required', 'array', 'min:1'],
                'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
                'items.*.unit_price' => ['required', 'numeric', 'min:0'],
                'items.*.discount_type' => ['nullable', 'string', 'in:fixed,percent'],
                'items.*.discount_value' => ['required', 'numeric', 'min:0'],
                'items.*.discount_amount' => ['required', 'numeric', 'min:0'],
                'items.*.line_total' => ['required', 'numeric', 'min:0'],
                'subtotal' => ['required', 'numeric', 'min:0'],
                'discount_type' => ['nullable', 'string', 'in:fixed,percent'],
                'discount_value' => ['required', 'numeric', 'min:0'],
                'discount_amount' => ['required', 'numeric', 'min:0'],
                'grand_total' => ['required', 'numeric', 'min:0'],
                'status' => ['nullable', 'string', 'in:draft,finalized,cancelled'],
            ]);

            // Invoice creation logic goes here
            DB::beginTransaction();

            if (empty($validated['invoice_no'])) {
                $validated['invoice_no'] = $this->generateInvoiceNumber();
            }

            $invoice = Invoice::create([
                'customer_id' => $validated['customer_id'] ?? null,
                'invoice_no' => $validated['invoice_no'],
                'invoice_date' => $validated['invoice_date'],
                'subtotal' => $validated['subtotal'],
                'discount_type' => $validated['discount_type'],
                'discount_value' => $validated['discount_value'],
                'discount_amount' => $validated['discount_amount'],
                'grand_total' => $validated['grand_total'],
                'status' => $validated['status'] ?? 'draft',
            ]);

            // Creation invoice items logic
            foreach ($validated['items'] as $itemData) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $itemData['product_id'],
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'discount_type' => $itemData['discount_type'] ?? null,
                    'discount_value' => $itemData['discount_value'],
                    'discount_amount' => $itemData['discount_amount'],
                    'line_total' => $itemData['line_total'],
                ]);
            }

            // Is Finalized: Create stock movements for the invoice items id finalized
            if ($invoice->status === 'finalized') {
                $this->createStockMovement($invoice);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully!',
                'data' => $invoice->load('items.product.category', 'customer'),
            ], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while creating invoice',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    // Invoice Show
    public function show(int $id)
    {
        try {
            $invoice = Invoice::with(['items.product.category', 'customer'])->find($id);

            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Invoice fetched successfully',
                'data' => $invoice,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching invoice',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    // Invoice Update & Status Change
    public function update(Request $request, int $id)
    {
        try {
            $invoice = Invoice::with('items')->find($id);
            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found',
                ], 404);
            }

            // Check invoice finalized
            if ($invoice->status === 'finalized') {
                return response()->json([
                    'success' => false,
                    'message' => 'Finalized invoices cannot be updated',
                ], 400);
            }

            $validated = $request->validate([
                'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
                'invoice_no' => ['sometimes', 'required', 'string', 'max:255', 'unique:invoices,invoice_no,' . $id],
                'invoice_date' => ['sometimes', 'required', 'date'],
                'items' => ['sometimes', 'required', 'array', 'min:1'],
                'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
                'items.*.quantity' => ['required', 'integer', 'min:1'],
                'items.*.unit_price' => ['required', 'numeric', 'min:0'],
                'items.*.discount_type' => ['nullable', 'string', 'in:fixed,percent'],
                'items.*.discount_value' => ['required', 'numeric', 'min:0'],
                'items.*.discount_amount' => ['required', 'numeric', 'min:0'],
                'items.*.line_total' => ['required', 'numeric', 'min:0'],
                'subtotal' => ['sometimes', 'required', 'numeric', 'min:0'],
                'discount_type' => ['nullable', 'string', 'in:fixed,percent'],
                'discount_value' => ['sometimes', 'required', 'numeric', 'min:0'],
                'discount_amount' => ['sometimes', 'required', 'numeric', 'min:0'],
                'grand_total' => ['sometimes', 'required', 'numeric', 'min:0'],
                'status' => ['sometimes', 'string', 'in:draft,finalized,cancelled'],
            ]);

            DB::beginTransaction();

            $oldStatus = $invoice->status;

            // If items found
            if (isset($validated['items'])) {
                // Delete old items
                $invoice->items()->delete();

                // Create new invoice items
                foreach ($validated['items'] as $itemData) {
                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'product_id' => $itemData['product_id'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'discount_type' => $itemData['discount_type'] ?? null,
                        'discount_value' => $itemData['discount_value'],
                        'discount_amount' => $itemData['discount_amount'],
                        'line_total' => $itemData['line_total'],
                    ]);
                }
            }

            $updateData = [
                'customer_id' => $validated['customer_id'] ?? $invoice->customer_id,
                'invoice_no' => $validated['invoice_no'] ?? $invoice->invoice_no,
                'invoice_date' => $validated['invoice_date'] ?? $invoice->invoice_date,
                'discount_type' => $validated['discount_type'] ?? $invoice->discount_type,
                'discount_value' => $validated['discount_value'] ?? $invoice->discount_value,
                'status' => $validated['status'] ?? $invoice->status,
            ];

            if (isset($validated['subtotal'])) {
                // push subtotal
                $updateData['subtotal'] = $validated['subtotal'];
                $updateData['discount_amount'] = $validated['discount_amount'];
                $updateData['grand_total'] = $validated['grand_total'];
            } elseif (isset($validated['discount_amount'])) {
                $updateData['discount_amount'] = $validated['discount_amount'];
                $updateData['grand_total'] = $validated['grand_total'];
            }

            $invoice->update($updateData);

            // If status changed to finalized, create stock movements
            $newStatus = $validated['status'] ?? $invoice->status;
            if ($oldStatus !== 'finalized' && $newStatus === 'finalized') {
                $this->createStockMovement($invoice->fresh());
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully',
                'data' => $invoice->load(['items.product.category', 'customer']),
            ], 200);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while updating invoice',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    // Invoice Delete without status finalized
    public function destroy(int $id)
    {
        try {
            $invoice = Invoice::find($id);
            if (!$invoice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invoice not found',
                ], 404);
            }

            if ($invoice->status === 'finalized') {
                return response()->json([
                    'success' => false,
                    'message' => 'Finalized invoices cannot be deleted',
                ], 400);
            }

            DB::beginTransaction();
            // Delete
            $invoice->items()->delete();
            $invoice->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Invoice deleted successfully!',
            ], 200);
        } catch (Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while delete invoice',
            ], 500);
        }
    }

    // Is Invoice finalized so called this method
    private function createStockMovement(Invoice $invoice)
    {
        foreach ($invoice->items as $item) {
            // Logic to create stock movement for each item
            $product = Product::findOrFail($item->product_id);

            // Check stock availability
            if ($product->stock_qty < $item->quantity) {
                throw new Exception("Insufficient stock for product: {$product->product_name}. Available Stock: {$product->stock_qty}, Required: {$item->quantity}");
            }

            // Create stock movement
            StockMovement::create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'type' => 'OUT',
                'note' => 'Stock Out for Invoice #' . $invoice->invoice_no,
                'invoice_id' => $invoice->id,
            ]);

            // Update Product stock quantity
            $product->stock_qty -= $item->quantity;
            $product->save();
        }
    }

    // Generate Custom Invoice Number
    private function generateInvoiceNumber()
    {
        // INV-2026-01-0001 details: INV-year-month - 0000
        // S-21/CO/2026/04/001 details: company name/corporate/year/week/invoice number
        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        // Get the last invoice number
        $lastInvoice = Invoice::where('invoice_no', 'like', "INV-{$year}-{$month}%")
            ->orderBy('invoice_no', 'desc')
            ->first();

        if ($lastInvoice) {
            $sequence = (int) substr($lastInvoice->invoice_no, -4);
            $sequence++;
        } else {
            $sequence = 1;
        }

        return sprintf('INV-%s-%s-%04d', $year, $month, $sequence);
    }
}
