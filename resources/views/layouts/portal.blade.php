<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Portal') - HMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-slate-900">
    @php
        $portalUser = auth()->user();
        $portalNav = $portalUser ? \App\Support\Portal::nav($portalUser) : [];
        $unread = $portalUser ? \App\Support\Portal::unreadCount($portalUser) : 0;
        $homeUrl = \App\Support\Portal::homeUrl($portalUser);
    @endphp

    <nav class="bg-white border-b border-slate-200 fixed z-30 w-full">
        <div class="px-6 py-3 flex items-center justify-between">
            <a href="{{ $homeUrl }}" class="text-lg font-bold text-indigo-600">HMS Portal</a>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('notices.board') }}" class="text-sm text-slate-600 hover:text-indigo-600">Notices</a>
                    <a href="{{ route('messages.index') }}" class="relative text-sm text-slate-600 hover:text-indigo-600">
                        Messages
                        @if($unread > 0)
                            <span class="absolute -top-2 -right-3 min-w-[18px] rounded-full bg-rose-500 px-1 text-center text-[10px] font-bold text-white">{{ $unread }}</span>
                        @endif
                    </a>
                    <a href="{{ route('profile.edit') }}" class="text-sm text-slate-600 hover:text-indigo-600">{{ auth()->user()->name }}</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <div class="flex pt-14 min-h-screen">
        @auth
            <aside class="hidden md:flex w-64 shrink-0 flex-col border-r border-slate-200 bg-white pt-4 pb-8 overflow-y-auto fixed top-14 bottom-0">
                <div class="px-4 mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">
                    {{ ucfirst(str_replace('_', ' ', auth()->user()->getRoleNames()->first() ?? 'staff')) }}
                </div>
                <nav class="px-3 space-y-0.5">
                    @foreach($portalNav as $item)
                        <a href="{{ route($item['route']) }}"
                           class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs($item['pattern']) ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-600 hover:bg-slate-50' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>
            </aside>
        @endauth

        <main class="flex-1 {{ auth()->check() ? 'md:ml-64' : '' }} p-6">
            @if(session('success'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</body>
</html>
