<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class pdfcotroller extends Controller
{


public function store(Request $request)
{
    // 1. التحقق من صحة الملف
    $request->validate([
        'pdf_file' => 'required|mimes:pdf|max:2048', // الحد الأقصى 2 ميجابايت
    ]);

    if ($request->hasFile('pdf_file')) {
        // 2. الحصول على الملف
        $file = $request->file('pdf_file');

        // 3. توليد اسم فريد للملف
        $fileName = time() . '_' . $file->getClientOriginalName();

        // 4. تخزين الملف في مجلد 'uploads/pdfs' داخل القرص العام (public storage)
        $path = $file->storeAs('uploads/pdfs', $fileName, 'public');

        // يمكنك هنا حفظ المسار في قاعدة البيانات
        // Document::create(['path' => $path]);

        return back()->with('success', 'تم رفع الملف بنجاح!');
    }

    return back()->with('error', 'فشل في رفع الملف.');
}
}
