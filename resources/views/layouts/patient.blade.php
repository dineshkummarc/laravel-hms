<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Patient Management Grid') - HMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <nav class="bg-white border-b border-gray-200 fixed z-30 w-full shadow-sm">
        <div class="px-6 py-3 flex items-center justify-between">
            <a href="{{ route('patient.dashboard') }}" class="text-xl font-bold text-blue-600 flex items-center gap-2">
                <span>🏥</span> HMS System Portal
            </a>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600 font-medium bg-gray-100 px-3 py-1.5 rounded-md">ID: {{ auth()->user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="text-sm font-semibold bg-red-50 text-red-600 px-4 py-2 rounded-lg hover:bg-red-100 transition">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="flex pt-16 overflow-hidden">
        <aside class="fixed top-0 left-0 z-20 flex flex-col w-64 h-full pt-16 bg-white border-r border-gray-200 shadow-sm">
            <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto px-4 space-y-1.5">
                <a href="{{ route('patient.dashboard') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.dashboard') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">📊</span> Dashboard
                </a>
                <a href="{{ route('patient.appointments.book.form') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.appointments.book.*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">🩺</span> Book Appointment
                </a>
                <a href="{{ route('patient.appointments.index') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.appointments.index') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">📅</span> Appointments
                </a>
                <a href="{{ route('patient.labs.buy.form') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.labs.buy.*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">🛍️</span> Buy Lab Tests
                </a>
                <a href="{{ route('patient.labs') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.labs') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">🧪</span> Lab Reports
                </a>
                <a href="{{ route('patient.records.index') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.records.*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">📁</span> Medical Records
                </a>
                <a href="{{ route('patient.prescriptions.index') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.prescriptions.*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">📜</span> Prescriptions
                </a>
                <a href="{{ route('patient.invoices.index') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.invoices.*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">💳</span> Invoices
                </a>
                <a href="{{ route('patient.feedback.index') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.feedback.*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">⭐</span> Feedback
                </a>
                <a href="{{ route('patient.ambulance.index') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('patient.ambulance.*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">🚑</span> Ambulance
                </a>
                <a href="{{ route('messages.index') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('messages.*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">✉️</span> Messages
                </a>
                <a href="{{ route('notices.board') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('notices.board') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">📢</span> Notices
                </a>
                <a href="{{ route('profile.edit') }}" class="flex items-center p-3 text-sm font-medium rounded-xl {{ request()->routeIs('profile.*') ? 'bg-blue-600 text-white shadow-md font-bold' : 'text-gray-600 hover:bg-gray-50' }}">
                    <span class="mr-3">👤</span> Profile
                </a>
            </div>
        </aside>

        <main class="h-full w-full bg-gray-50 relative overflow-y-auto ml-64 p-8 min-h-screen">
            @if(session('success'))
                <div class="bg-green-50 border border-green-400 text-green-800 px-4 py-3.5 rounded-xl mb-6 shadow-sm flex items-center gap-2 font-medium">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>

</body>
</html>