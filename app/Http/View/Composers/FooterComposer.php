<?php

namespace App\Http\View\Composers;

use App\Models\ContactPage;
use App\Models\SocialLink;
use Illuminate\View\View;

class FooterComposer
{
    public function compose(View $view): void
    {
        $view->with('footerContact', ContactPage::first());
        $view->with('footerSocials', SocialLink::active()->get());
    }
}
