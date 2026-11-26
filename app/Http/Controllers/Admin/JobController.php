<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobStatusLog;
use App\Models\ProductionJob;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Ei user ja ja job dekhte pabe tar query.
     * - Tenant scope: Unit er global scope diye automatic
     * - Unit manager / operator: shudhu nijer unit er job
     */
    private function visibleJobs()
    {
        $user = auth()->user();

        $query = ProductionJob::with(['unit', 'order.items'])
            ->whereIn('unit_id', Unit::select('id'));

        if (in_array($user->role, [User::ROLE_UNIT_MANAGER, User::ROLE_OPERATOR], true)) {
            $query->where('unit_id', $user->unit_id);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $query = $this->visibleJobs();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $jobs = $query->latest()->paginate(15)->withQueryString();

        $noUnit = in_array(auth()->user()->role, [User::ROLE_UNIT_MANAGER, User::ROLE_OPERATOR], true)
            && !auth()->user()->unit_id;

        return view('admin.jobs.index', compact('jobs', 'noUnit'));
    }

    public function show($id)
    {
        $job = $this->visibleJobs()->with('logs.actor')->findOrFail($id);

        return view('admin.jobs.show', compact('job'));
    }

    /**
     * Pore'r stage e nao (queued -> printing -> qc -> ready -> delivered)
     */
    public function advance($id)
    {
        $job = $this->visibleJobs()->findOrFail($id);
        $user = auth()->user();
        $next = $job->nextStatus();

        if (!$next) {
            return back()->with('error', 'The job is already in the final stage.');
        }

        // Delivery confirm shudhu manager / press responsible korte pare
        if ($next === 'delivered' && $user->role === User::ROLE_OPERATOR) {
            abort(403, 'Operator cannot confirm delivery.');
        }

        $this->moveTo($job, $next, 'Stage advanced');

        return back()->with('success', "Job moved to: {$next}");
    }

    /**
     * QC fail hole rework e pathao (qc -> printing)
     */
    public function rework(Request $request, $id)
    {
        $data = $request->validate([
            'note' => 'required|string|max:255',
        ]);

        $job = $this->visibleJobs()->findOrFail($id);

        if ($job->status !== 'qc') {
            return back()->with('error', 'Rework can only be done from the QC stage.');
        }

        $this->moveTo($job, 'printing', 'Rework: ' . $data['note'], true);

        return back()->with('success', 'Job sent back to printing for rework.');
    }

    private function moveTo(ProductionJob $job, string $to, string $note, bool $isRework = false): void
    {
        DB::transaction(function () use ($job, $to, $note, $isRework) {
            $from = $job->status;

            $job->status = $to;

            if ($isRework) {
                $job->rework_count = $job->rework_count + 1;
            }
            if ($to === 'delivered') {
                $job->completed_at = now();
            }
            $job->save();

            JobStatusLog::create([
                'production_job_id' => $job->id,
                'actor_id'          => auth()->id(),
                'from_status'       => $from,
                'to_status'         => $to,
                'note'              => $note,
            ]);

            // Order er status o sync koro
            $orderStatus = match ($to) {
                'printing'  => 'in_production',
                'qc'        => 'qc',
                'ready'     => 'ready',
                'delivered' => 'delivered',
                default     => null,
            };

            if ($orderStatus && $job->order) {
                $job->order->update(['status' => $orderStatus]);
            }
        });
    }
}