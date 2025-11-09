@extends('layout.auth')
@section("title","reset password")
@section('content')
    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="text-center">
                    <h2 class="mt-6 text-3xl font-bold tracking-tight text-gray-900">Reset Password</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Please enter your new password below.
                    </p>
                </div>
    
                <div class="mt-8">
                    <div class="mt-6">
                        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $email }}">
    
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                                <div class="mt-1">
                                    <input id="password" name="password" type="password" required
                                        class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm @error('password') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" />
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                                <div class="mt-1">
                                    <input id="password_confirmation" name="password_confirmation" type="password" required
                                        class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm" />
                                </div>
                            </div>
    
                            <div>
                                <button type="submit"
                                    class="flex w-full justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    Reset Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection