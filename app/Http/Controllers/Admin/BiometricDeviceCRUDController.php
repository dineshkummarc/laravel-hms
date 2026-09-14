<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BiometricDevice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BiometricDeviceCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Biometric devices',
            'resource' => 'admin.biometric-devices',
            'items'    => BiometricDevice::latest()->paginate(20),
            'columns'  => ['name', 'serial_number', 'location', 'is_online'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Biometric device',
            'resource' => 'admin.biometric-devices',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);
        $validated['api_token'] = $validated['api_token'] ?: Str::random(40);
        $validated['is_online'] = $request->boolean('is_online');

        BiometricDevice::create($validated);

        return redirect()->route('admin.biometric-devices.index')->with('success', 'Biometric device created.');
    }

    public function show(BiometricDevice $biometric_device): View
    {
        return view('admin.crud.show', [
            'title'    => 'Biometric device',
            'resource' => 'admin.biometric-devices',
            'item'     => $biometric_device,
            'columns'  => ['name', 'serial_number', 'ip_address', 'location', 'api_token', 'is_online', 'last_ping_at'],
        ]);
    }

    public function edit(BiometricDevice $biometric_device): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Biometric device',
            'resource' => 'admin.biometric-devices',
            'item'     => $biometric_device,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, BiometricDevice $biometric_device): RedirectResponse
    {
        $validated = $this->validated($request, $biometric_device->id);
        $validated['is_online'] = $request->boolean('is_online');

        $biometric_device->update($validated);

        return redirect()->route('admin.biometric-devices.index')->with('success', 'Biometric device updated.');
    }

    public function destroy(BiometricDevice $biometric_device): RedirectResponse
    {
        $biometric_device->delete();

        return redirect()->route('admin.biometric-devices.index')->with('success', 'Biometric device deleted.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $ignore = $id ?? 'NULL';

        return $request->validate([
            'name'          => 'required|string|max:255',
            'serial_number' => 'required|string|max:255|unique:biometric_devices,serial_number,'.$ignore,
            'ip_address'    => 'nullable|string|max:45',
            'location'      => 'nullable|string|max:255',
            'api_token'     => 'nullable|string|max:255|unique:biometric_devices,api_token,'.$ignore,
            'is_online'     => 'sometimes|boolean',
            'last_ping_at'  => 'nullable|date',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'name', 'label' => 'Name', 'type' => 'text', 'required' => true],
            ['name' => 'serial_number', 'label' => 'Serial number', 'type' => 'text', 'required' => true],
            ['name' => 'ip_address', 'label' => 'IP address', 'type' => 'text', 'required' => false],
            ['name' => 'location', 'label' => 'Location', 'type' => 'text', 'required' => false],
            ['name' => 'api_token', 'label' => 'API token', 'type' => 'text', 'required' => false],
            ['name' => 'is_online', 'label' => 'Online', 'type' => 'checkbox', 'required' => false],
            ['name' => 'last_ping_at', 'label' => 'Last ping at', 'type' => 'datetime-local', 'required' => false],
        ];
    }
}
