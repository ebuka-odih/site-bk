<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::ordered()->get();

        return Inertia::render('Admin/PaymentMethods/Index', [
            'paymentMethods' => $paymentMethods,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/PaymentMethods/Create', [
            'types' => [
                'bank' => 'Bank Transfer',
                'crypto' => 'Cryptocurrency',
                'paypal' => 'PayPal',
                'wire_transfer' => 'Wire Transfer',
                'custom' => 'Custom Method',
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:bank,crypto,paypal,wire_transfer,custom',
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:payment_methods,key',
            'enabled' => 'boolean',
            'min_amount' => 'required|integer|min:0',
            'max_amount' => 'nullable|integer|min:0',
            'processing_time' => 'nullable|string|max:255',
            'fee_percentage' => 'nullable|numeric|min:0|max:100',
            'fee_fixed' => 'nullable|integer|min:0',
            'requires_reference' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'configuration' => 'nullable|array',
            'instructions' => 'nullable|array',
            'notes' => 'nullable|array',
        ]);

        // Set defaults
        $validated['enabled'] = $validated['enabled'] ?? true;
        $validated['requires_reference'] = $validated['requires_reference'] ?? true;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return Inertia::render('Admin/PaymentMethods/Show', [
            'paymentMethod' => $paymentMethod,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return Inertia::render('Admin/PaymentMethods/Edit', [
            'paymentMethod' => $paymentMethod,
            'types' => [
                'bank' => 'Bank Transfer',
                'crypto' => 'Cryptocurrency',
                'paypal' => 'PayPal',
                'wire_transfer' => 'Wire Transfer',
                'custom' => 'Custom Method',
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:bank,crypto,paypal,wire_transfer,custom',
            'name' => 'required|string|max:255',
            'key' => 'required|string|max:255|unique:payment_methods,key,' . $paymentMethod->id,
            'enabled' => 'boolean',
            'min_amount' => 'required|integer|min:0',
            'max_amount' => 'nullable|integer|min:0',
            'processing_time' => 'nullable|string|max:255',
            'fee_percentage' => 'nullable|numeric|min:0|max:100',
            'fee_fixed' => 'nullable|integer|min:0',
            'requires_reference' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'configuration' => 'nullable|array',
            'instructions' => 'nullable|array',
            'notes' => 'nullable|array',
        ]);

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.edit', $paymentMethod)
            ->with('success', 'Payment method updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully!');
    }

    /**
     * Toggle the enabled status of a payment method.
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'enabled' => !$paymentMethod->enabled,
        ]);

        $status = $paymentMethod->enabled ? 'enabled' : 'disabled';
        
        return redirect()->back()
            ->with('success', "Payment method {$status} successfully!");
    }
}
