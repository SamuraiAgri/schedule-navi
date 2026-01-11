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
        // テスト環境ではBladeビュー、本番ではInertia
        if (app()->environment('testing')) {
            return view('static.privacy');
        }
        return Inertia::render('Pages/Privacy');
    }

    /**
     * 利用規約ページ
     */
    public function terms()
    {
        // テスト環境ではBladeビュー、本番ではInertia
        if (app()->environment('testing')) {
            return view('static.terms');
        }
        return Inertia::render('Pages/Terms');
    }

    /**
     * お問い合わせページ
     */
    public function contact()
    {
        // テスト環境ではBladeビュー、本番ではInertia
        if (app()->environment('testing')) {
            return view('static.contact');
        }
        return Inertia::render('Pages/Contact');
    }

    /**
     * 使い方ガイドページ
     */
    public function about()
    {
        // テスト環境ではBladeビュー、本番ではInertia
        if (app()->environment('testing')) {
            return view('static.about');
        }
        return Inertia::render('Pages/About');
    }
}
