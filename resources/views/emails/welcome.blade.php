@extends('emails.layouts.base')

@section('content')
<h1>Welcome to ChoreBusters, {{ $user->name }}!</h1>
<p>We're excited to have you on board. Now you can start organizing chores with your family members.</p>
<p>To get started, you can:</p>
<ul>
    <li>Create your first family group</li>
    <li>Invite family members to join</li>
    <li>Set up chores and assign them</li>
</ul>
<div class="button-container">
    <a href="{{ url('/') }}" class="button">Go to our website</a>
</div>
@endsection