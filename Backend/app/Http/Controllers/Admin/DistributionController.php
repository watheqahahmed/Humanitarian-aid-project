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

        // أضف رابط الملف ليكون صالحاً للعرض
        $distributions->transform(function ($distribution) {
            if ($distribution->proof_file) {
                // رابط كامل للملف من public/storage
                $distribution->proof_file_url = asset('storage/' . $distribution->proof_file);
            } else {
                $distribution->proof_file_url = null;
            }
            return $distribution;
        });

        return response()->json($distributions);
    }

    /**
     * Store a newly created distribution in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Distribution::class);

        $validatedData = $request->validate([
            'volunteer_id' => ['required', 'exists:users,id', Rule::exists('users', 'id')->where('role', 'volunteer')],
            'beneficiary_id' => 'required|exists:users,id',
            'donation_id' => 'required|exists:donations,id',
            'delivery_status' => 'required|in:assigned,in_progress,completed',
        ]);

        $distribution = Distribution::create($validatedData);

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
        $this->authorize('view', $distribution);
        $distribution->load(['volunteer', 'beneficiary', 'donation']);

        if ($distribution->proof_file) {
            $distribution->proof_file_url = asset('storage/' . $distribution->proof_file);
        } else {
            $distribution->proof_file_url = null;
        }

        return response()->json($distribution);
    }

    /**
     * Update the specified distribution in storage.
     */
    public function update(Request $request, Distribution $distribution)
    {
        $this->authorize('update', $distribution);

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
        $this->authorize('delete', $distribution);
        $distribution->delete();
        return response()->json(null, 204);
    }
}
