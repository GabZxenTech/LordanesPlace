<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        return view('admin.packages', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'price'      => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'duration'   => 'nullable|string|max:255',
            'description'=> 'nullable|string',
        ]);

        Package::create($request->all());

        return back()->with('success', 'Package created successfully.');
    }

    public function update(Request $request, $id)
    {
        $package = Package::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'price'      => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'duration'   => 'nullable|string|max:255',
            'description'=> 'nullable|string',
        ]);

        $package->update($request->all());

        return back()->with('success', 'Package updated successfully.');
    }

    public function destroy($id)
    {
        Package::findOrFail($id)->delete();
        return back()->with('success', 'Package deleted successfully.');
    }
}
