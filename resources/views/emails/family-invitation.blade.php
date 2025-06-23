@extends('emails.layouts.base')

@section('content')
<h1>You've been invited to join {{ $family->name }}!</h1>
<p>{{ $inviter->name }} has invited you to join their family group on Family Chores App.</p>
<p>By joining, you'll be able to:</p>
<ul>
    <li>View and complete assigned chores</li>
    <li>Earn points for completed tasks</li>
    <li>Redeem rewards with your points</li>
</ul>
<div>
    Go to our website now to accept or decline this invitation!
</div>
<div class="button-container">
    <a href="{{ url('/') }}" class="button">Go to our website</a>
</div>
@endsection