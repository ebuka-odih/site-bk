@extends('emails.layout')

@section('title', 'Transfer Confirmation')

@section('tag', 'Transfer Update')

@section('headline', 'Your Transfer Is On Its Way')

@section('intro')
Hello {{ $senderName }}, your transfer has been processed successfully.
@endsection

@section('content')
    <div class="summary">
        <dl>
            <dt>Amount Sent</dt>
            <dd>{{ $amount }}</dd>

            <dt>Status</dt>
            <dd>{{ $status }}</dd>

            <dt>Reference</dt>
            <dd>{{ $reference }}</dd>

            <dt>Date</dt>
            <dd>{{ $date }}</dd>

            @if($fee)
                <dt>Transfer Fee</dt>
                <dd>{{ $fee }}</dd>
            @endif

            <dt>Available Balance</dt>
            <dd>{{ $availableBalance }}</dd>
        </dl>
    </div>

    @if($isWireTransfer)
        <h2>Wire Transfer Details</h2>
        <div class="summary">
            <dl>
                @if($wireDetails['beneficiary_name'])
                    <dt>Beneficiary</dt>
                    <dd>{{ $wireDetails['beneficiary_name'] }}</dd>
                @endif

                @if($wireDetails['bank_name'])
                    <dt>Bank</dt>
                    <dd>{{ $wireDetails['bank_name'] }}</dd>
                @endif

                @if($wireDetails['account_number'])
                    <dt>Account Number</dt>
                    <dd>{{ $wireDetails['account_number'] }}</dd>
                @endif

                @if($wireDetails['routing_number'])
                    <dt>Routing Number</dt>
                    <dd>{{ $wireDetails['routing_number'] }}</dd>
                @endif

                @if($wireDetails['swift_code'])
                    <dt>SWIFT/BIC</dt>
                    <dd>{{ $wireDetails['swift_code'] }}</dd>
                @endif

                @if($wireDetails['beneficiary_address'])
                    <dt>Beneficiary Address</dt>
                    <dd>{{ $wireDetails['beneficiary_address'] }}</dd>
                @endif
            </dl>
        </div>
    @else
        <h2>Recipient Details</h2>
        <div class="summary">
            <dl>
                <dt>Recipient</dt>
                <dd>{{ $recipientName }}</dd>

                <dt>Account Number</dt>
                <dd>{{ $recipientAccount }}</dd>
            </dl>
        </div>
    @endif

    @if($description)
        <p>{{ $description }}</p>
    @endif

    <a href="{{ url('/transactions') }}" class="cta-button">View Transfer</a>

    <p class="muted">If you do not recognize this transfer, please contact support immediately.</p>
@endsection

