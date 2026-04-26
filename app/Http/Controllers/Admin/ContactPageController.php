<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactPage;
use Illuminate\Http\Request;

class ContactPageController extends Controller
{
    public function edit()
    {
        $contact = ContactPage::firstOrCreate([], [
            'address_ar'  => '',
            'address_en'  => '',
            'phone'       => '',
            'email'       => '',
            'map_url'     => '',
            'mon_fri_ar'  => '',
            'mon_fri_en'  => '',
            'saturday_ar' => '',
            'saturday_en' => '',
            'sunday_ar'   => '',
            'sunday_en'   => '',
        ]);

        return view('admin.contact.edit', compact('contact'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'address_ar'  => 'required|string',
            'address_en'  => 'required|string',
            'phone'       => 'required|string',
            'email'       => 'required|email',
            'map_url'     => 'required|string',
            'mon_fri_ar'  => 'required|string',
            'mon_fri_en'  => 'required|string',
            'saturday_ar' => 'required|string',
            'saturday_en' => 'required|string',
            'sunday_ar'   => 'required|string',
            'sunday_en'   => 'required|string',
        ]);

        ContactPage::firstOrCreate([])->update($request->all());

        return redirect()->route('admin.contact.edit')
            ->with('success', 'Contact page updated successfully ✅');
    }
}