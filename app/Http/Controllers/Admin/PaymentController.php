<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::with(['invoice', 'tenant'])->latest()->paginate(15);
        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $invoices = Invoice::with('tenant')
            ->whereIn('status', ['sent', 'overdue'])
            ->get();
        $selectedInvoice = $request->query('invoice_id');
        return view('admin.payments.create', compact('invoices', 'selectedInvoice'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string|max:50',
            'payment_reference' => 'required|string|max:100|unique:payments,payment_reference',
            'notes' => 'nullable|string',
        ]);

        // Idempotency check
        if (Payment::where('payment_reference', $validated['payment_reference'])->exists()) {
            return back()->with('error', 'Payment already recorded.');
        }

        DB::beginTransaction();
        try {
            $invoice = Invoice::findOrFail($validated['invoice_id']);

            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'tenant_id' => $invoice->tenant_id,
                'amount' => $validated['amount'],
                'currency' => $invoice->currency,
                'payment_method' => $validated['payment_method'],
                'payment_reference' => $validated['payment_reference'],
                'status' => 'completed',
                'paid_at' => now(),
                'notes' => $validated['notes'] ?? null,
            ]);

            // Invoice paid?
            $totalPaid = $invoice->payments()->where('status', 'completed')->sum('amount');
            if ($totalPaid >= $invoice->amount) {
                $invoice->update(['status' => 'paid', 'paid_at' => now()]);
            }

            // Auto-renew subscription
            if ($invoice->subscription && $invoice->subscription->auto_renew) {
                $sub = $invoice->subscription;
                $newEnd = $sub->billing_cycle === 'monthly'
                    ? $sub->ends_at->addMonth()
                    : $sub->ends_at->addYear();

                $sub->update(['status' => 'active', 'ends_at' => $newEnd]);
            }

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', 'Payment recorded. Invoice updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
}