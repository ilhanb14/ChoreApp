@extends('emails.layouts.base')

@section('content')
<h1>Reward Claimed</h1>
<p>{{ $child->name }} redeemed: <strong>{{ $reward->reward }}</strong></p>

<p>Details:</p>
<ul>
    <li>Reward: {{ $reward->reward }}</li>
    <li>Points Cost: {{ $reward->points }}</li>
    <li>Family: {{ $family->name }}</li>
    <li>{{ $child->name }}'s Current Points: {{ $child->families()->where('family_id', $family->id)->first()->pivot->points }}</li>
</ul>

<p>If there is something wrong with this reward, please discuss it with {{ $child->name }} directly.</p>

<p style="font-size: 12px; color: #999;">
    You're receiving this email because you're listed as an adult in the {{ $family->name }} family.
</p>

<div class="button-container">
    <a href="{{ url('/') }}" class="button">Go to our website</a>
</div>
@endsection