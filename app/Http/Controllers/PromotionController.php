<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Promotion;

class PromotionController extends Controller
{
    /**
     * Display a listing of promotions.
     */
    public function index()
    {
        $promotions = Promotion::all();
        return view('admin-views.promotions.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new promotion.
     */
    public function create()
    {
        return view('admin-views.promotions.create');
    }

    /**
     * Store a newly created promotion in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'promotion_type' => 'required|string|max:255',
            'price' => 'required|numeric',
            'maximum_allowed' => 'required|string|max:255',
        ]);

        Promotion::create($request->all());
        return redirect()->route('admin.promotions.index')->with('success', 'Promotion added successfully!');
    }

    /**
     * Display the specified promotion.
     */
    public function show(Promotion $promotion)
    {
        return view('admin-views.promotions.show', compact('promotion'));
    }

    /**
     * Show the form for editing the specified promotion.
     */
    public function edit(Promotion $promotion)
    {
        return view('promotions.edit', compact('promotion'));
    }

    /**
     * Update the specified promotion in the database.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'price' => 'required|numeric',
        ]);

        $promotion->update($request->all());

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion updated successfully!');
    }

    /**
     * Remove the specified promotion from the database.
     */
    public function destroy(Promotion $promotion)
    {
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion deleted successfully!');
    }
}
