<?php

namespace App\Http\Controllers;

use App\Models\SellerAvailability;
use Illuminate\Http\Request;

class SellerAvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SellerAvailability $sellerAvailability)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SellerAvailability $sellerAvailability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */   
     public function update(Request $request, SellerAvailability $sellerAvailability)
    {
        $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $sellerAvailability->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json([
            'message' => 'Availability updated successfully!',
            'data' => $sellerAvailability
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SellerAvailability $sellerAvailability)
    {
        //
    }
}
