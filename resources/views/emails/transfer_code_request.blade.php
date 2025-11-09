@extends('emails.layout')

@section('title', 'Transfer Code Request')

@section('tag', 'Transfer Code')

@section('headline', 'Support Team, Action Required')

@section('intro')
{{ $user->name }} (User ID: {{ $user->id }}) has requested a transfer authorization code.
@endsection

@section('content')
    <div class="summary">
        <dl>
            <dt>User Email</dt>
            <dd>{{ $user->email }}</dd>

            @if($user->phone)
                <dt>Phone</dt>
                <dd>{{ $user->phone }}</dd>
            @endif

            <dt>Wallet Account</dt>
            <dd>{{ $transferDetails['account_number'] ?? 'N/A' }}</dd>
        </dl>
    </div>

    <h2>Transfer Details</h2>
    <div class="summary">
        <dl>
            <dt>Transfer Type</dt>
            <dd>{{ ucfirst($transferDetails['transfer_type']) }}</dd>

            <dt>Amount</dt>
            <dd>{{ number_format($transferDetails['amount'], 2) }} {{ $transferDetails['currency'] ?? 'USD' }}</dd>

            @if(!empty($transferDetails['description']))
                <dt>Description</dt>
                <dd>{{ $transferDetails['description'] }}</dd>
            @endif
        </dl>
    </div>

    @if($transferDetails['transfer_type'] === 'internal')
        <h2>Recipient Details</h2>
        <div class="summary">
            <dl>
                <dt>Recipient Account</dt>
                <dd>{{ $transferDetails['recipient_account'] }}</dd>
            </dl>
        </div>
    @elseif($transferDetails['transfer_type'] === 'wire')
        <h2>Wire Transfer Details</h2>
        <div class="summary">
            <dl>
                <dt>Beneficiary Name</dt>
                <dd>{{ $transferDetails['wire_beneficiary_name'] }}</dd>

                <dt>Beneficiary Address</dt>
                <dd>{{ $transferDetails['wire_beneficiary_address'] }}</dd>

                <dt>Bank Name</dt>
                <dd>{{ $transferDetails['wire_bank_name'] }}</dd>

                <dt>Account Number</dt>
                <dd>{{ $transferDetails['wire_account_number'] }}</dd>

                <dt>Routing Number</dt>
                <dd>{{ $transferDetails['wire_routing_number'] }}</dd>

                @if(!empty($transferDetails['wire_swift_code']))
                    <dt>SWIFT/BIC</dt>
                    <dd>{{ $transferDetails['wire_swift_code'] }}</dd>
                @endif
            </dl>
        </div>
    @endif

    <p>
        Please review the request and respond to the user at
        <a href="mailto:{{ $user->email }}">{{ $user->email }}</a> with the appropriate transfer code.
    </p>
@endsection

