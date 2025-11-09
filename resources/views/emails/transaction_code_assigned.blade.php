@extends('emails.layout')

@section('title', 'Transaction Authorization Code')

@section('tag', 'Authorization Code')

@section('headline', 'Your Transaction Code Is Ready')

@section('intro')
Use the details below to complete your pending transaction securely.
@endsection

@section('content')
    <div class="summary">
        <dl>
            <dt>Authorization Code</dt>
            <dd><span class="code-display" style="letter-spacing: 0.12em;">{{ $code }}</span></dd>

            <dt>Code Type</dt>
            <dd>{{ $type }}</dd>

            <dt>Permitted Amount</dt>
            <dd>{{ $amount }}</dd>

            @if($expiresAt)
                <dt>Expires</dt>
                <dd>{{ $expiresAt }}</dd>
            @endif
        </dl>
    </div>

    <p>Enter this code when prompted to authorize your transaction. Once used, the code becomes invalid.</p>

    @if($notes)
        <p class="muted">{{ $notes }}</p>
    @endif
@endsection

