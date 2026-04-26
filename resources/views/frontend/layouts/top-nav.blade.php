<div id="top-nav" class="top-nav style-one bg-black md:h-[44px] h-[30px]">
    <div class="container mx-auto h-full">
        <div class="top-nav-main flex justify-between max-md:justify-center h-full">
            <div class="left-content flex items-center gap-5 max-md:hidden">

                {{-- Language Switcher --}}
                <div class="choose-type choose-language flex items-center gap-1.5">
                    <div class="select relative">
                        <p class="selected caption2 text-white">
                            {{ app()->getLocale() === 'ar' ? 'العربية' : 'English' }}
                        </p>
                        <ul class="list-option bg-white">
                            <li class="caption2 {{ app()->getLocale() === 'ar' ? 'active' : '' }}"
                                onclick="window.location='{{ route('switch.lang', 'ar') }}'">
                                العربية
                            </li>
                            <li class="caption2 {{ app()->getLocale() === 'en' ? 'active' : '' }}"
                                onclick="window.location='{{ route('switch.lang', 'en') }}'">
                                English
                            </li>
                        </ul>
                    </div>
                    <i class="ph ph-caret-down text-xs text-white"></i>
                </div>

              

            </div>

            {{-- Center Text --}}
            @if($announcement)
            <div class="text-center text-button-uppercase text-white flex items-center">
                {{ app()->getLocale() === 'ar' ? $announcement->text_ar : $announcement->text_en }}
            </div>
            @endif

            {{-- Social Links from DB --}}
            <div class="right-content flex items-center gap-5 max-md:hidden">
                @foreach($topNavSocials ?? [] as $social)
                <a href="{{ $social->url }}" target="_blank" rel="noopener">
                    <i class="{{ $social->icon }} text-white"></i>
                </a>
                @endforeach
            </div>

        </div>
    </div>
</div>
