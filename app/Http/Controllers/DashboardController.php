<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // index
    public function index()
    {
        return view('admin.dashboard.index');
    }

    // Category
    public function category()
    {
        return view('admin.categories.index');
    }

    // Product
    public function product()
    {
        return view('admin.products.index');
    }

    // Stock
    public function stock()
    {
        return view('admin.stock.index');
    }


    // POST
    public function pos()
    {
        return view('admin.pos.index');
    }


    // Invoice
    public function invoice()
    {
        return view('admin.invoices.index');
    }
}
