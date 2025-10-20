<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Invoice System')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-50">

    <!-- Navigation -->
    <nav class="bg-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <i class="fas fa-file-invoice text-white text-2xl mr-3"></i>
                        <span class="text-white text-xl font-bold">Invoice System</span>
                    </a>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('dashboard') }}"
                        class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                    <a href="{{ route('clients.index') }}"
                        class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-users mr-1"></i> Clients
                    </a>
                    <a href="{{ route('invoices.index') }}"
                        class="text-white hover:bg-blue-700 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-file-invoice-dollar mr-1"></i> Invoices
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Success Message -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-12 py-6 border-t">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-600">
            <p>&copy; 2025 iCreativez Technologies. All rights reserved.</p>
        </div>
    </footer>

</body>

</html>
