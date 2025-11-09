@extends('emails.layout')

@section('title', '[Admin Alert] ' . $title)

@section('tag', strtoupper($levelLabel))

@section('headline', $title)

@section('intro')
{{ $message }}
@endsection

@section('content')
    @if(!empty($details))
        <h2>Details</h2>
        <div class="summary">
            <dl>
                @foreach($details as $detail)
                    <dt>{{ $detail['label'] }}</dt>
                    <dd>{{ $detail['value'] }}</dd>
                @endforeach
            </dl>
        </div>
    @endif

    @if($actionUrl)
        <a href="{{ $actionUrl }}" class="cta-button">View in Admin</a>
    @endif
@endsection

