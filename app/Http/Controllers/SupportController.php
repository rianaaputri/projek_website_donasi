<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    // Tampilkan halaman FAQ
    public function faq()
    {
        return view('pages.faq');
    }

    // Tampilkan halaman Cara Berdonasi
    public function donationGuide()
    {
        return view('pages.donation-guide');
    }

    // Tampilkan halaman Hubungi Kami
    public function contact()
    {
        return view('pages.contact');
    }

    // Proses form Hubungi Kami
    public function sendContact(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'subject' => 'required|string|max:150',
            'message' => 'required|string|max:1000',
        ]);

        // 🔽 Di sini nanti bisa tambah kirim email atau simpan ke database
        // Untuk sekarang, kita cuma kirim notifikasi sukses

        return back()->with('success', 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi kami.');
    }

    // Tampilkan halaman Pusat Bantuan
    public function supportCenter()
    {
        return view('pages.support-center');
    }
}