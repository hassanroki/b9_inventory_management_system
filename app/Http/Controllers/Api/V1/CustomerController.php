<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Index
    public function index()
    {
        $customers = Customer::latest()->get();

        return response()->json([
            'status' => true,
            'data' => $customers
        ]);
    }


    // Customer Store
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'mobile' => 'required'
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Customer created',
            'data' => $customer
        ]);
    }


    // Customer Show
    public function show(int $id)
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $customer
        ]);
    }


    // Customer Update
    public function update(Request $request, int $id)
    {
        $customer = Customer::findOrFail($id);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'description' => $request->description
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Customer updated'
        ]);
    }


    // Customer Delete
    public function destroy(int $id)
    {
        Customer::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Customer deleted'
        ]);
    }
}
