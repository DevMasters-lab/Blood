@extends('layouts.admin')

@section('content')
<div class="flex justify-center mt-10">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-red-600">User Login</h2>
        <form action="{{ route('user.login') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Phone</label>
                <input type="text" name="phone" class="w-full border p-2 rounded" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border p-2 rounded" required>
            </div>
            <button type="submit" class="w-full bg-red-600 text-white p-2 rounded hover:bg-red-700">Login</button>
        </form>
        <div class="mt-4 text-center">
            <a href="{{ route('register') }}" class="text-blue-500 hover:underline">No account? Register here</a>
        </div>
    </div>
</div>
@endsection