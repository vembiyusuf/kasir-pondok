<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Kasir Pondok</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-green-100 to-green-300 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-green-700 mb-6">Login Kasir Pondok</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>{{ $errors->first() }}</strong>
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" required
                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-green-400">
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                    class="w-full bg-green-600 text-white py-2 rounded-xl font-semibold hover:bg-green-700 transition duration-300">
                    Login
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            &copy; {{ date('Y') }} Kasir Pondok. All rights reserved.
        </p>
    </div>

</body>

</html>
