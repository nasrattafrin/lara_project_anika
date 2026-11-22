<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['tenant', 'subscription.plan'])->latest()->paginate(15);
        return view('admin.invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $subscriptions = Subscription::with(['tenant', 'plan'])
            ->where('status', 'active')
            ->get();
        $selectedSubscription = $request->query('subscription_id');
        return view('admin.invoices.create', compact('subscriptions', 'selectedSubscription'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'subtotal' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'due_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string',
        ]);

        $subscription = Subscription::with('tenant')->findOrFail($validated['subscription_id']);

        $subtotal = $validated['subtotal'];
        $tax = $validated['tax'] ?? 0;
        $discount = $validated['discount'] ?? 0;
        $amount = $subtotal + $tax - $discount;

        $invoice = Invoice::create([
            'tenant_id' => $subscription->tenant_id,
            'subscription_id' => $subscription->id,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'discount' => $discount,
            'amount' => $amount,
            'currency' => $subscription->currency,
            'status' => 'sent',
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice ' . $invoice->invoice_number . ' created!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['tenant', 'subscription.plan', 'payments']);
        return view('admin.invoices.show', compact('invoice'));
    }
}