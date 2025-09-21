<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Display a listing of the donations (Admin only).
     */
    public function index()
    {
        $this->authorize('viewAny', Donation::class); // Authorization
        $donations = Donation::all();
        return response()->json($donations);
    }

    /**
     * Store a newly created donation (Admin only).
     */
    public function store(Request $request)
    {
        $this->authorize('create', Donation::class); // Authorization

        $validatedData = $request->validate([
            'donor_name' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,received,distributed',
        ]);

        $donation = Donation::create($validatedData);

        return response()->json($donation, 201);
    }

    /**
     * Display the specified donation (Admin only).
     */
    public function show(Donation $donation)
    {
        $this->authorize('view', $donation); // Authorization
        return response()->json($donation);
    }

    /**
     * Update the specified donation (Admin only).
     */
    public function update(Request $request, Donation $donation)
    {
        $this->authorize('update', $donation); // Authorization

        $validatedData = $request->validate([
            'donor_name' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|in:pending,received,distributed',
        ]);

        $donation->update($validatedData);

        return response()->json($donation);
    }

    /**
     * Remove the specified donation (Admin only).
     */
    public function destroy(Donation $donation)
    {
        $this->authorize('delete', $donation); // Authorization

        $donation->delete();

        return response()->json(null, 204);
    }

    /**
     * Store a new donation from any visitor (Public access).
     */
    public function storePublic(Request $request)
    {
        $validatedData = $request->validate([
            'donor_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
        ]);

        // الحالة الافتراضية للتبرع القادم من زائر عام
        $validatedData['status'] = 'pending';

        $donation = Donation::create($validatedData);

        return response()->json([
            'message' => 'تم استلام التبرع بنجاح',
            'donation' => $donation,
        ], 201);
    }
}
