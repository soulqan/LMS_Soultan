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

                <div class="mt-6 flex items-center justify-between text-sm">
                    <a href="{{ route('student.register') }}" class="font-medium text-blue-600 hover:text-blue-700">Create an account</a>
                    <a href="{{ url('/admin/forgot-password') }}" class="font-medium text-slate-600 hover:text-slate-900">Forgot password?</a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
