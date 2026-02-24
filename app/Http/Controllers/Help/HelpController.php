<?php

namespace App\Http\Controllers\Help;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        $faqs = $this->getFaqs();

        return view('Help.index', compact('faqs'));
    }

    /**
     * Murojaat formasini yuborish
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
            'phone'   => 'nullable|string|max:20',
            'type'    => 'required|string|in:general,technical,payment,shop,account,other',
        ], [
            'subject.required' => 'Mavzuni kiriting.',
            'message.required' => 'Xabarni kiriting.',
            'message.min'      => 'Xabar kamida 10 ta belgidan iborat bo\'lishi kerak.',
        ]);

        $student = auth()->user();
        $types   = [
            'general'   => 'Umumiy savol',
            'technical' => 'Texnik muammo',
            'payment'   => "To'lov masalasi",
            'shop'      => "Do'kon masalasi",
            'account'   => 'Hisob muammosi',
            'other'     => 'Boshqa',
        ];

        // Admin emailga yuborish
        try {
            Mail::raw(
                "Yangi murojaat!\n\n" .
                "Foydalanuvchi: {$student->name}\n" .
                "Telefon: {$request->phone}\n" .
                "Tur: {$types[$request->type]}\n" .
                "Mavzu: {$request->subject}\n\n" .
                "Xabar:\n{$request->message}\n\n" .
                "---\n" .
                "Platforma orqali yuborildi.",
                function ($message) use ($request, $student, $types) {
                    $message->to('xoliqulovmuhammadnabi@gmail.com')
                        ->subject("[{$types[$request->type]}] {$request->subject} — {$student->name}");
                }
            );
        } catch (\Exception $e) {
            // Email yuborilmasa ham log qilamiz, lekin xato ko'rsatmaymiz
            Log::error('Help form email failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'subject' => $request->subject,
            ]);
        }

        // DB ga saqlamoqchi bo'lsangiz (agar HelpTicket model bo'lsa):
        // HelpTicket::create([...]);

        return back()->with('success', '✅ Murojaatingiz qabul qilindi! Administrator tez orada bog\'lanadi.');
    }

    /**
     * PDF qo'llanmani yuklab berish
     */
    public function downloadPdf()
    {
        // PDF faylni public/guides/ papkasiga qo'yish kerak
        // Yoki storage/app/public/guides/ papkasiga
        $pdfPath = public_path('guides/qollanma.pdf');

        if (!file_exists($pdfPath)) {
            // Fallback: storage papkasidan izlaymiz
            $storagePath = storage_path('app/public/guides/qollanma.pdf');
            if (file_exists($storagePath)) {
                $pdfPath = $storagePath;
            } else {
                abort(404, "PDF fayl topilmadi. Administrator bilan bog'laning.");
            }
        }

        return response()->download($pdfPath, "EduPlatform-Qollanma.pdf", [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="EduPlatform-Qollanma.pdf"',
        ]);
    }

    /**
     * FAQ ma'lumotlari
     */
    private function getFaqs(): array
    {
        return [
            [
                'question' => "Platformaga kira olmayapman, nima qilaman?",
                'answer'   => "Avval telefon raqamingiz va parolni tekshiring. Katta-kichik harflarga e'tibor bering. Parolni unutgan bo'lsangiz, 'Parolni unutdim' tugmasini bosing yoki +998 88 115 61 01 raqamiga qo'ng'iroq qiling.",
            ],
            [
                'question' => "Coinlarim qaerdan ko'rishim mumkin?",
                'answer'   => "Dashboard (bosh sahifa) da coin balansiz ko'rsatiladi. Shuningdek, o'ng yuqori burchakdagi profilingizga bosganda ham ko'rishingiz mumkin.",
            ],
            [
                'question' => "Do'kondan mahsulot sotib olsam, coinlarim qacharlar?",
                'answer'   => "Ha. Buyurtma berganingizda coinlar zudlik bilan hisobingizdan yechiladi. Agar buyurtmani bekor qilsangiz (faqat 'Kutilmoqda' holatida), coinlar qaytariladi.",
            ],
            [
                'question' => "Buyurtmam qachon yetkaziladi?",
                'answer'   => "Administrator buyurtmangizni ko'rib chiqib tasdiqlagach, 1–3 ish kuni ichida yetkaziladi. Buyurtma holati o'zgarganda bildirishnoma keladi.",
            ],
            [
                'question' => "Parolimni o'zgartirish mumkinmi?",
                'answer'   => "Ha. Avatar menyu → Settings → Xavfsizlik bo'limiga kiring va yangi parol o'rnating. Kamida 8 ta belgi bo'lishi shart.",
            ],
            [
                'question' => "Profil rasmimni o'zgartira olamanmi?",
                'answer'   => "Ha. Avatar menyusidan 'Your Profile' ga kiring, 'Tahrirlash' tugmasini bosib yangi rasm yuklang. PNG, JPG formatlar qo'llab-quvvatlanadi.",
            ],
            [
                'question' => "Platforma mobil telefonda ishlaydi?",
                'answer'   => "Ha, platforma to'liq responsive — mobil, planshet va kompyuterlarda mukammal ishlaydi. Maxsus ilova talab qilinmaydi.",
            ],
            [
                'question' => "Coin yig'ish uchun nima qilish kerak?",
                'answer'   => "Darslarga qatnashish, vazifalarni o'z vaqtida topshirish, imtihonlarda yaxshi natija ko'rsatish va faol ishtirok etish orqali coin yig'ishingiz mumkin. Batafsil PDF qo'llanmada ko'ring.",
            ],
        ];
    }
}
