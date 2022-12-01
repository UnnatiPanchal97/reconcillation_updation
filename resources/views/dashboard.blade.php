@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@section('toolbar title','Dashboard')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 text-gray-400 fw-bold fs-4">
                    Hello 
                    @if(Auth::user()->user_role === config('params.user_role.super_admin')) {{"Super Admin !!"}} <br> @endif 
                    @if(Auth::user()->user_role === config('params.user_role.admin')) {{"Admin !!"}} <br>@endif
                    You're logged in! 
                </div>
            </div>
        </div>
    </div>
@endsection
