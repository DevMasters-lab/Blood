@extends('layouts.admin')

@section('content')
    <h2 class="text-2xl font-bold mb-4">Dashboard Overview</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded shadow border-l-4 border-blue-500">
            <h3 class="text-gray-500">Total Users</h3>
            <p class="text-3xl font-bold">{{ $stats['users'] }}</p>
        </div>

        <div class="bg-white p-6 rounded shadow border-l-4 border-red-500">
            <h3 class="text-gray-500">Open Requests</h3>
            <p class="text-3xl font-bold">{{ $stats['requests'] }}</p>
        </div>

        <div class="bg-white p-6 rounded shadow border-l-4 border-yellow-500">
            <h3 class="text-gray-500">Pending Donations</h3>
            <p class="text-3xl font-bold">{{ $stats['donations'] }}</p>
        </div>
    </div>
@endsection