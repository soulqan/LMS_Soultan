<x-layouts.app title="Login | LearningHub">
    <div class="min-h-dvh bg-slate-50 px-4 py-12">
        <div class="mx-auto max-w-md">
            <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
                <div class="mb-8 text-center">
                    <a href="{{ route('home') }}" class="mb-4 inline-flex items-center justify-center">
                        <span class="inline-flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 text-white">
                            <x-app-icon name="book-open" class="h-5 w-5" />
                        </span>
                    </a>
                    <h1 class="text-2xl font-semibold text-slate-900">Login</h1>
                    <p class="mt-2 text-sm text-slate-600">Access your LearningHub account.</p>
                </div>

                <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700" for="email">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700" for="password">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-3 text-sm text-slate-700">
                        <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <span>Remember me</span>
                    </label>

                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Login
                    </button>
                </form>

                <div class="relative my-6 flex items-center py-1">
                    <div class="flex-grow border-t border-slate-200"></div>
                    <span class="flex-shrink mx-4 text-slate-400 text-xs font-semibold uppercase tracking-wider">Or continue with</span>
                    <div class="flex-grow border-t border-slate-200"></div>
                </div>

                <a href="{{ route('auth.google.redirect') }}"
                    class="inline-flex w-full items-center justify-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-500">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4" />
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853" />
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05" />
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335" />
                    </svg>
                    <span>Google</span>
                </a>

                <div class="mt-6 flex items-center justify-between text-sm">
                    <a href="{{ route('student.register') }}" class="font-medium text-blue-600 hover:text-blue-700">Register</a>
                    <a href="{{ url('/admin/forgot-password') }}" class="font-medium text-slate-600 hover:text-slate-900">Forgot password?</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
