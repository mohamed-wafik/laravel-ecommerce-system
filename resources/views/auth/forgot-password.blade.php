@extends('layout.auth')
@section("title","Forget Password")
@section('content')
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="text-center">
                    <h2 class="mt-6 text-3xl font-bold tracking-tight text-gray-900">Forgot your password?</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        No worries! Enter your email address and we'll send you a link to reset your password.
                    </p>
                </div>
    
                @if (session('status'))
                    <div class="mt-6">
                        <div class="rounded-md bg-green-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-green-800">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
    
                <div class="mt-8">
                    <div class="mt-6">
                        <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                <div class="mt-1">
                                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                                        class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-primary-500 focus:outline-none focus:ring-primary-500 sm:text-sm @error('email') border-red-300 text-red-900 placeholder-red-300 focus:border-red-500 focus:ring-red-500 @enderror" />
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
    
                            <div>
                                <button type="submit"
                                    class="flex w-full justify-center rounded-md border border-transparent bg-primary-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                                    Send Reset Link
                                </button>
                            </div>
                        </form>
                    </div>
    
                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                            Return to login
                        </a>
                    </div>
                </div>
            </div>
        </div>
@endsection