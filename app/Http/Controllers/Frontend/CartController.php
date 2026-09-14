<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = Session::get('lab_cart', []);
        $total = collect($cart)->sum('cost');

        return view('frontend.cart.index', compact('cart', 'total'));
    }

    public function add(LabTest $labTest): RedirectResponse
    {
        $cart = Session::get('lab_cart', []);

        if (! isset($cart[$labTest->id])) {
            $cart[$labTest->id] = [
                'id' => $labTest->id,
                'name' => $labTest->name,
                'cost' => $labTest->cost,
                'department' => $labTest->department?->name,
                'qty' => 1,
            ];
            Session::put('lab_cart', $cart);
        }

        return back()->with('success', $labTest->name.' added to your testing cart.');
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $cart = Session::get('lab_cart', []);
        if (isset($cart[$id])) {
            $qty = max(1, (int) $request->input('qty', 1));
            $cart[$id]['qty'] = $qty;
            Session::put('lab_cart', $cart);
        }

        return back()->with('success', 'Cart updated.');
    }

    public function remove(int $id): RedirectResponse
    {
        $cart = Session::get('lab_cart', []);
        unset($cart[$id]);
        Session::put('lab_cart', $cart);

        return back()->with('success', 'Test removed from cart.');
    }

    public function clear(): RedirectResponse
    {
        Session::forget('lab_cart');

        return back()->with('success', 'Cart cleared.');
    }
}
