<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order; 
use Illuminate\Http\Request;

class DistribusiController extends Controller
{
    public function index()
    {
        $distribusiData = Order::with(['items.product'])->latest()->get();
        $totalDistribusi = $distribusiData->count();
        $sedangDiproses = $distribusiData->count(); 

        return view('admin.distribusi', compact('distribusiData', 'totalDistribusi', 'sedangDiproses'));
    }
}