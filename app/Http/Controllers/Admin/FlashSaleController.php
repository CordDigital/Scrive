<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FlashSaleController extends Controller
{
    public function edit()
    {
        $flashSale = FlashSale::first() ?? new FlashSale();
        return view('admin.flash-sale.edit', compact('flashSale'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title_ar'         => 'required|string',
            'title_en'         => 'required|string',
            'subtitle_ar'      => 'nullable|string',
            'subtitle_en'      => 'nullable|string',
            'image'            => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'min_amount'       => 'required|numeric|min:0',
            'ends_at'          => 'required|date|after:now',
        ]);

        $data = array_merge(
            $request->except('_token', '_method', 'image'),
            ['is_active' => $request->boolean('is_active')]
        );

        if ($request->hasFile('image')) {
            $existingFlashSale = FlashSale::first();
            if ($existingFlashSale && $existingFlashSale->image) {
                Storage::disk('public')->delete($existingFlashSale->image);
            }
            $data['image'] = $request->file('image')->store('flash-sales', 'public');
        }

        FlashSale::updateOrCreate(['id' => 1], $data);

        return back()->with('success', 'تم تحديث Flash Sale');
    }
}
