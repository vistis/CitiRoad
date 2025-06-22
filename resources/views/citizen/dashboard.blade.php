@extends('layouts.citizen.app')

@section('title', 'Citizen Dashboard')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Citizen Dashboard') }}
    </h2>
@endsection

@section('slot')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="text-gray-900">
                    Welcome,
                    {{ $user->first_name }} {{ $user->last_name }}!
                </div>
                <table class="table-auto mt-3 border border-gray-300 w-full text-gray-900">
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">National ID</td>
                            <td class="border border-gray-300 p-2">{{ $user->id }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Status</td>
                            <td class="border border-gray-300 p-2">{{ $user->status }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Name</td>
                            <td class="border border-gray-300 p-2">{{ $user->first_name }} {{ $user->last_name }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Email</td>
                            <td class="border border-gray-300 p-2">{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Phone Number</td>
                            <td class="border border-gray-300 p-2">{{ $user->phone_number }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Province ID</td>
                            <td class="border border-gray-300 p-2">{{ $user->province_id }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Address</td>
                            <td class="border border-gray-300 p-2">{{ $user->address }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Date of Birth</td>
                            <td class="border border-gray-300 p-2">{{ $user->date_of_birth }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Gender</td>
                            <td class="border border-gray-300 p-2">{{ $user->gender }}</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 font-bold">Created At</td>
                            <td class="border border-gray-300 p-2">{{ $user->created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
