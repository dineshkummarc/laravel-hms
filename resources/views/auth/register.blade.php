<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Registration - Secure Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen py-10">

    <div class="w-full max-w-xl bg-white p-8 rounded-lg shadow-lg">
        <div class="mb-6 flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-sm text-blue-600 hover:underline flex items-center">
                &larr; Back to Home
            </a>
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">
                Already registered? Login
            </a>
        </div>

        <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Create Patient Account</h2>
        
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-6 text-sm">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-5">
            @csrf
            
            <div class="space-y-4">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Basic Information</h3>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ old('name') }}" 
                        class="w-full mt-1 border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="email" required value="{{ old('email') }}" 
                        class="w-full mt-1 border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
    <label class="block text-sm font-medium text-gray-700">Phone Number / Emergency Contact <span class="text-red-500">*</span></label>
    <input type="text" name="phone" required value="{{ old('phone') }}" 
        class="w-full mt-1 border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
</div>
            </div>

            <div class="space-y-4 pt-2">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Medical Profile</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Age <span class="text-red-500">*</span></label>
                        <input type="number" name="age" required value="{{ old('age') }}" min="18"
                            class="w-full mt-1 border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gender <span class="text-red-500">*</span></label>
                        <select name="gender" required class="w-full mt-1 border border-gray-300 rounded p-2 bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="" disabled {{ old('gender') ? '' : 'selected' }}>Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Blood Group</label>
                        <select name="blood_group" class="w-full mt-1 border border-gray-300 rounded p-2 bg-white focus:ring-blue-500 focus:border-blue-500">
                            <option value="" {{ old('blood_group') ? '' : 'selected' }}>Unknown / Optional</option>
                            <option value="A+" {{ old('blood_group') == 'A+' ? 'selected' : '' }}>A+</option>
                            <option value="A-" {{ old('blood_group') == 'A-' ? 'selected' : '' }}>A-</option>
                            <option value="B+" {{ old('blood_group') == 'B+' ? 'selected' : '' }}>B+</option>
                            <option value="B-" {{ old('blood_group') == 'B-' ? 'selected' : '' }}>B-</option>
                            <option value="AB+" {{ old('blood_group') == 'AB+' ? 'selected' : '' }}>AB+</option>
                            <option value="AB-" {{ old('blood_group') == 'AB-' ? 'selected' : '' }}>AB-</option>
                            <option value="O+" {{ old('blood_group') == 'O+' ? 'selected' : '' }}>O+</option>
                            <option value="O-" {{ old('blood_group') == 'O-' ? 'selected' : '' }}>O-</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="space-y-4 pt-2">
                <h3 class="text-lg font-semibold text-gray-700 border-b pb-2">Security</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="8"
                            class="w-full mt-1 border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password_confirmation" required minlength="8"
                            class="w-full mt-1 border border-gray-300 rounded p-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded font-semibold text-lg hover:bg-blue-700 transition mt-6 shadow-md">
                Register & Access Portal
            </button>
        </form>
    </div>
</body>
</html>