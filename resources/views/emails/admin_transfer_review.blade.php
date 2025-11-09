@extends('emails.layout')

@section('title', 'Transfer Pending Approval')

@section('tag', 'Admin Review')

@section('headline', 'Transfer Awaiting Approval')

@section('intro')
A customer has reached the final confirmation step for a transfer. Review the details below and approve it in the admin dashboard.
@endsection

@section('content')
    <h2>Transaction Summary</h2>
    <div class="summary">
        <dl>
            <dt>Reference</dt>
            <dd>{{ $transaction->reference }}</dd>

            <dt>Status</dt>
            <dd>{{ ucfirst($transaction->status) }}</dd>

            <dt>Transfer Type</dt>
            <dd>{{ ucfirst($metadata['transfer_type'] ?? $transaction->type) }}</dd>

            <dt>Amount</dt>
            <dd>{{ $amount }}</dd>

            @if($fee)
                <dt>Fee</dt>
                <dd>{{ $fee }}</dd>
            @endif

            <dt>Total Debit</dt>
            <dd>{{ $totalDebit }}</dd>

            @if($createdAt)
                <dt>Requested At</dt>
                <dd>{{ $createdAt }}</dd>
            @endif
        </dl>
    </div>

    <h2>Account Holder</h2>
    <div class="summary">
        <dl>
            <dt>Name</dt>
            <dd>{{ $user?->name }}</dd>

            <dt>Email</dt>
            <dd>{{ $user?->email }}</dd>

            @if($user?->phone)
                <dt>Phone</dt>
                <dd>{{ $user->phone }}</dd>
            @endif

            @if($user?->wallet)
                <dt>Wallet Account</dt>
                <dd>{{ $user->wallet->account_number }}</dd>

                <dt>Current Balance</dt>
                <dd>{{ $walletBalance }}</dd>
            @endif
        </dl>
    </div>

    @if($isWireTransfer)
        <h2>Wire Transfer Details</h2>
        <div class="summary">
            <dl>
                <dt>Beneficiary</dt>
                <dd>{{ $metadata['beneficiary_name'] ?? 'N/A' }}</dd>

                <dt>Bank Name</dt>
                <dd>{{ $metadata['bank_name'] ?? 'N/A' }}</dd>

                <dt>Account Number</dt>
                <dd>{{ $metadata['account_number'] ?? 'N/A' }}</dd>

                <dt>Routing Number</dt>
                <dd>{{ $metadata['routing_number'] ?? 'N/A' }}</dd>

                @if(!empty($metadata['swift_code']))
                    <dt>SWIFT/BIC</dt>
                    <dd>{{ $metadata['swift_code'] }}</dd>
                @endif

                @if(!empty($metadata['beneficiary_address']))
                    <dt>Beneficiary Address</dt>
                    <dd>{{ $metadata['beneficiary_address'] }}</dd>
                @endif
            </dl>
        </div>
    @else
        <h2>Recipient Details</h2>
        <div class="summary">
            <dl>
                <dt>Recipient Name</dt>
                <dd>{{ $transaction->beneficiary_name ?? $recipient?->name ?? 'N/A' }}</dd>

                <dt>Recipient Account</dt>
                <dd>{{ $transaction->beneficiary_account_number ?? $recipient?->wallet?->account_number ?? 'N/A' }}</dd>
            </dl>
        </div>
    @endif

    @if(!empty($transaction->description))
        <p><strong>Description:</strong> {{ $transaction->description }}</p>
    @endif

    <a href="{{ url('/admin/transactions/' . $transaction->id) }}" class="cta-button">Review Transaction</a>
@endsection

