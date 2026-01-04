<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PageController extends Controller
{
    /**
     * プライバシーポリシーページ
     */
    public function privacy()
    {
        return Inertia::render('Pages/Privacy');
    }

    /**
     * 利用規約ページ
     */
    public function terms()
    {
        return Inertia::render('Pages/Terms');
    }

    /**
     * お問い合わせページ
     */
    public function contact()
    {
        return Inertia::render('Pages/Contact');
    }
}
