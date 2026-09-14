<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password help - HMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-md w-full bg-white rounded-xl shadow p-8">
        <h1 class="text-xl font-bold mb-2">Password recovery</h1>
        <p class="text-sm text-gray-600 mb-4">For clinical safety, password resets are handled by hospital IT / reception rather than an open email link.</p>
        <p class="text-sm text-gray-700 mb-6">Email <strong>admin@hospital.local</strong> or visit the front desk with photo ID. Staff accounts use the seeded demo passwords listed in README.md.</p>
        <a href="{{ route('login') }}" class="text-blue-600 text-sm hover:underline">Back to login</a>
    </div>
</body>
</html>
