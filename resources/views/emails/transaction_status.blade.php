@extends('emails.layout')

@section('title', $type . ' ' . $status)

@section('tag', 'Transaction Update')

@section('headline', 'Transaction ' . $status)

@section('intro')
Hello {{ $recipientName }}, here is a quick summary of your recent {{ strtolower($type) }}.
@endsection

@section('content')
    <div class="summary">
        <dl>
            <dt>Amount</dt>
            <dd>{{ $amount }}</dd>

            <dt>Status</dt>
            <dd>{{ $status }}</dd>

            <dt>Reference</dt>
            <dd>{{ $reference }}</dd>

            @if($createdAt)
                <dt>Date</dt>
                <dd>{{ $createdAt }}</dd>
            @endif

            @if($beneficiaryName)
                <dt>Counterparty</dt>
                <dd>{{ $beneficiaryName }}</dd>
            @endif

            @if($beneficiaryAccount)
                <dt>Account</dt>
                <dd>{{ $beneficiaryAccount }}</dd>
            @endif

            @if($fee)
                <dt>Fee</dt>
                <dd>{{ $fee }}</dd>
            @endif

            @if($availableBalance)
                <dt>Available Balance</dt>
                <dd>{{ $availableBalance }}</dd>
            @endif
        </dl>
    </div>

    @if($description)
        <p>{{ $description }}</p>
    @endif

    @if($actionUrl)
        <a href="{{ $actionUrl }}" class="cta-button">View Transaction</a>
    @endif

    <p class="muted">If you did not authorize this activity, please contact support immediately.</p>
@endsection

