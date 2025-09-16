<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DistributionController extends Controller
{
    /**
     * Display a listing of distributions.
     */
    public function index()
    {
        $distributions = Distribution::with(['volunteer', 'beneficiary', 'donation'])->get();
        return response()->json($distributions);
    }

    /**
     * Store a newly created distribution in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Distribution::class); // Authorization

        $validatedData = $request->validate([
            'volunteer_id' => ['required', 'exists:users,id', Rule::exists('users', 'id')->where('role', 'volunteer')],
            'beneficiary_id' => 'required|exists:users,id',
            'donation_id' => 'required|exists:donations,id',
            'delivery_status' => 'required|in:assigned,in_progress,completed',
        ]);

        $distribution = Distribution::create($validatedData);

        // إنشاء إشعار للمتطوع عند تعيين مهمة توزيع جديدة
        Notification::create([
            'user_id' => $distribution->volunteer_id,
            'message' => 'تم تعيين مهمة توزيع جديدة لك.',
            'type' => 'new_delivery_assignment',
            'status' => 'unread',
        ]);

        return response()->json($distribution, 201);
    }

    /**
     * Show the specified distribution.
     */
    public function show(Distribution $distribution)
    {
        $this->authorize('view', $distribution); // Authorization
        $distribution->load(['volunteer', 'beneficiary', 'donation']);
        return response()->json($distribution);
    }

    /**
     * Update the specified distribution in storage.
     */
    public function update(Request $request, Distribution $distribution)
    {
        $this->authorize('update', $distribution); // Authorization

        $validatedData = $request->validate([
            'delivery_status' => 'required|in:assigned,in_progress,completed',
        ]);

        $distribution->update($validatedData);

        return response()->json($distribution);
    }

    /**
     * Remove the specified distribution from storage.
     */
    public function destroy(Distribution $distribution)
    {
        $this->authorize('delete', $distribution); // Authorization
        $distribution->delete();
        return response()->json(null, 204);
    }
}
