<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AmbulanceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AmbulanceCRUDController extends Controller
{
    public function index(): View
    {
        return view('admin.crud.index', [
            'title'    => 'Ambulance requests',
            'resource' => 'admin.ambulance-requests',
            'items'    => AmbulanceRequest::with('patient')->latest()->paginate(20),
            'columns'  => ['caller_name', 'caller_phone', 'pickup_address', 'priority', 'status', 'vehicle_code'],
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title'    => 'Ambulance request',
            'resource' => 'admin.ambulance-requests',
            'fields'   => $this->fields(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        AmbulanceRequest::create($this->validated($request));

        return redirect()->route('admin.ambulance-requests.index')->with('success', 'Ambulance request created.');
    }

    public function show(AmbulanceRequest $ambulance_request): View
    {
        return view('admin.crud.show', [
            'title'    => 'Ambulance request',
            'resource' => 'admin.ambulance-requests',
            'item'     => $ambulance_request,
            'columns'  => ['caller_name', 'caller_phone', 'pickup_address', 'destination', 'priority', 'notes', 'status', 'vehicle_code', 'driver_name', 'dispatched_at'],
        ]);
    }

    public function edit(AmbulanceRequest $ambulance_request): View
    {
        return view('admin.crud.edit', [
            'title'    => 'Ambulance request',
            'resource' => 'admin.ambulance-requests',
            'item'     => $ambulance_request,
            'fields'   => $this->fields(),
        ]);
    }

    public function update(Request $request, AmbulanceRequest $ambulance_request): RedirectResponse
    {
        $data = $this->validated($request);
        if (in_array($data['status'], ['dispatched', 'en_route'], true) && ! $ambulance_request->dispatched_at) {
            $data['dispatched_at'] = now();
        }
        $ambulance_request->update($data);

        return redirect()->route('admin.ambulance-requests.index')->with('success', 'Ambulance request updated.');
    }

    public function destroy(AmbulanceRequest $ambulance_request): RedirectResponse
    {
        $ambulance_request->delete();

        return redirect()->route('admin.ambulance-requests.index')->with('success', 'Ambulance request deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'caller_name' => 'required|string|max:255',
            'caller_phone' => 'required|string|max:30',
            'pickup_address' => 'required|string|max:255',
            'destination' => 'nullable|string|max:255',
            'priority' => 'required|in:standard,urgent,critical',
            'notes' => 'nullable|string',
            'status' => 'required|in:requested,dispatched,en_route,arrived,completed,cancelled',
            'vehicle_code' => 'nullable|string|max:50',
            'driver_name' => 'nullable|string|max:255',
        ]);
    }

    private function fields(): array
    {
        return [
            ['name' => 'caller_name', 'label' => 'Caller name', 'type' => 'text', 'required' => true],
            ['name' => 'caller_phone', 'label' => 'Caller phone', 'type' => 'text', 'required' => true],
            ['name' => 'pickup_address', 'label' => 'Pickup address', 'type' => 'text', 'required' => true],
            ['name' => 'destination', 'label' => 'Destination', 'type' => 'text', 'required' => false],
            ['name' => 'priority', 'label' => 'Priority', 'type' => 'select', 'required' => true, 'options' => ['standard' => 'Standard', 'urgent' => 'Urgent', 'critical' => 'Critical']],
            ['name' => 'notes', 'label' => 'Notes', 'type' => 'textarea', 'required' => false],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => ['requested' => 'Requested', 'dispatched' => 'Dispatched', 'en_route' => 'En route', 'arrived' => 'Arrived', 'completed' => 'Completed', 'cancelled' => 'Cancelled']],
            ['name' => 'vehicle_code', 'label' => 'Vehicle code', 'type' => 'text', 'required' => false],
            ['name' => 'driver_name', 'label' => 'Driver name', 'type' => 'text', 'required' => false],
        ];
    }
}
