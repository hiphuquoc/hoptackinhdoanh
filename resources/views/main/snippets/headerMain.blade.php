<!-- START:: Menu Desktop -->
<div class="headerMain">
    <div class="container">
        <div class="headerMain_item">
            <ul>
                <li>
                    <a href="/" title="Trang chủ {{ config('main_'.env('APP_NAME').'.company_name') }}">
                        <div>
                            <svg><use xlink:href="#icon_home"></use></svg>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="/ve-chung-toi">
                        <div>Về chúng tôi</div>
                    </a>
                </li>
                <li>
                    <div>
                        <div>Dịch vụ</div>
                    </div>
                    @include('main.snippets.megaMenuServices')
                </li>
            </ul>
        </div>
        <div class="headerMain_item" style="flex:0 0 70px;">
            @if(Request::is('/'))
                <a href="/" title="Trang chủ {{ config('main_'.env('APP_NAME').'.company_name') }}" class="logoSquare"><h1 style="display:none;">{{ config('main.description') }}</h1></a>
            @else 
                <a href="/" title="Trang chủ {{ config('main_'.env('APP_NAME').'.company_name') }}" class="logoSquare"></a>
            @endif
        </div>
        <div class="headerMain_item">
            <ul style="justify-content:flex-end;">
                <li>
                    <div>
                        <div>Sàn kết nối</div>
                    </div>
                    <div class="normalMenu">
                        <ul>
                            @php
                                $exchanges  = \App\Models\Exchange::select('*')
                                                ->with('seo', 'seos')
                                                ->get();
                            @endphp    
                            @foreach($exchanges as $exchange)     
                                @include('main.snippets.itemMenu', [
                                    'itemMenu'  => $exchange,
                                ])
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="/tin-tuc">
                        <div>Tin tức - Kiến thức</div>
                    </a>
                    <div class="normalMenu">
                        <ul>
                            @php
                                $categoryBlogs = \App\Models\CategoryBlog::select('*')
                                                    ->whereHas('seo', function($query){
                                                        $query->where('level', '>', 1);
                                                    })
                                                    ->with('seo', 'seos')
                                                    ->get();
                            @endphp    
                            @foreach($categoryBlogs as $categoryBlog)     
                                @include('main.snippets.itemMenu', [
                                    'itemMenu'  => $categoryBlog,
                                ])
                            @endforeach
                        </ul>
                    </div>

                </li>
                
                
                <li>
                    <div>
                        <div>
                            <svg><use xlink:href="#icon_bars"></use></svg>
                        </div>
                    </div>
                    {{-- <div class="normalMenu" style="margin-right:1.5rem;right:0;">
                        <ul>
                            <li>
                                <a href="/lien-he-hitour" title="Liên hệ {{ config('main_'.env('APP_NAME').'.company_name') }}">
                                    <div>Liên hệ</div>
                                </a>
                            </li>
                        </ul>
                    </div> --}}
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Background Hover -->
{{-- <div class="backgroundHover"></div> --}}
<!-- END:: Menu Desktop -->

<!-- START:: Header Mobile -->
<div class="header">
    <div class="container">
        @if(Request::is('/'))
            <div class="header_logo">
                <a href="/" title="Trang chủ {{ config('main_'.env('APP_NAME').'.company_name') }}" class="logo">
                    <h1 style="display:none;">{{ config('main.description') }}</h1>
                </a>
            </div>
        @else 
            <div class="header_arrow">
                <a href="history.back()" title="Quay lại trang trước">
                    <i class="fa-solid fa-arrow-left-long"></i>
                </a>
            </div>
            <div class="header_logo">
                <a href="/" title="Trang chủ {{ config('main_'.env('APP_NAME').'.company_name') }}" class="logo"></a>
            </div>
        @endif
        <!-- Menu Mobile -->
        <div class="header_menuMobile">
            <div class="header_menuMobile_item" onclick="openCloseElemt('nav-mobile');">
                <i class="fa-solid fa-bars" style="font-size:1.5rem;margin-top:0.25rem;color:#fff;"></i>
            </div>
        </div>
    </div>
</div>
<!-- END:: Header Mobile -->

<!-- START:: Menu Mobile -->
<div id="nav-mobile" style="display:none;">
    <div class="nav-mobile">
        <div class="nav-mobile_bg" onclick="openCloseElemt('nav-mobile');"></div>
        <div class="nav-mobile_main customScrollBar-y">
            <div class="nav-mobile_main__exit" onclick="openCloseElemt('nav-mobile');">
                <i class="fas fa-times"></i>
            </div>
            <a href="/" title="Trang chủ {{ config('main_'.env('APP_NAME').'.company_name') }}" style="display:flex;justify-content:center;margin-top:5px;margin-bottom:-10px;">
                <div class="logoSquare"></div>
            </a>
            <ul>
                <li>
                    <a href="/" title="Trang chủ {{ config('main_'.env('APP_NAME').'.company_name') }}">
                        <div><i class="fas fa-home"></i>Trang chủ</div>
                        <div class="right-icon"></div>
                    </a>
                </li>
                {{-- <li>
                    <div>
                        <i class="fas fa-umbrella-beach"></i>
                        Tour nước ngoài
                    </div>
                    <span class="right-icon" onclick="showHideListMenuMobile(this);"><i class="fas fa-chevron-right"></i></span>
                    <ul style="display:none;">
                    @foreach($dataTourContinent as $tourContinent)
                        <li>
                            <a href="/{{ $tourContinent->seo->slug_full ?? null}}" title="{{ $tourContinent->name ?? $tourContinent->seo->title ?? null }}">
                                <div>{{ $tourContinent->name ?? $tourContinent->seo->title ?? null }}</div>
                            </a>
                        </li>
                    @endforeach
                    </ul>
                </li> --}}
                <li>
                    <div onclick="showHideListMenuMobile(this);">
                        <i class="fas fa-umbrella-beach"></i>
                        <span class="nav-mobile_main__title">Tour Du Lịch</span>
                        <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <ul style="display:none;">
                        @if(!empty($dataBD))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Tour Biển Đảo</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($dataBD as $tourBD)
                                    <li>
                                        <a href="/{{ $tourBD->seo->slug_full ?? null }}" title="{{ $tourBD->name ?? $tourBD->seo->title ?? null }}">
                                            <div>{{ $tourBD->name ?? $tourBD->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($dataMB))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Tour Miền Bắc</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($dataMB as $tourMB)
                                    <li>
                                        <a href="/{{ $tourMB->seo->slug_full ?? null }}" title="{{ $tourMB->name ?? $tourMB->seo->title ?? null }}">
                                            <div>{{ $tourMB->name ?? $tourMB->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($dataMT))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Tour Miền Trung</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($dataMT as $tourMT)
                                    <li>
                                        <a href="/{{ $tourMT->seo->slug_full ?? null }}" title="{{ $tourMT->name ?? $tourMT->seo->title ?? null }}">
                                            <div>{{ $tourMT->name ?? $tourMT->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($dataMN))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Tour Miền Nam</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($dataMN as $tourMN)
                                    <li>
                                        <a href="/{{ $tourMN->seo->slug_full ?? null }}" title="{{ $tourMN->name ?? $tourMN->seo->title ?? null }}">
                                            <div>{{ $tourMN->name ?? $tourMN->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
                
                
                @if(!empty($dataShip)&&$dataShip->isNotEmpty())
                    <li>
                        <div onclick="showHideListMenuMobile(this);">
                            <i class="fas fa-ship"></i>
                            <span class="nav-mobile_main__title">Vé Tàu Cao Tốc</span>
                            <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                        </div>
                        <ul style="display:none;">
                        @foreach($dataShip as $shipLocation)
                            <li>
                                <a href="/{{ $shipLocation->seo->slug_full ?? null }}" title="{{ $shipLocation->name ?? $shipLocation->seo->title ?? null }}">
                                    <div>{{ $shipLocation->name ?? $shipLocation->seo->title ?? null }}</div>
                                </a>
                            </li>
                        @endforeach
                        </ul>
                    </li>
                @endif

                <li>
                    <div onclick="showHideListMenuMobile(this);">
                        <i class="fa-solid fa-bed"></i>
                        <span class="nav-mobile_main__title">Khách Sạn</span>
                        <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <ul style="display:none;">
                        @if(!empty($hotelBD))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Khách Sạn Biển Đảo</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($hotelBD as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>{{ $h->name ?? $h->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($hotelMB))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Khách Sạn Miền Bắc</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($hotelMB as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>{{ $h->name ?? $h->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($hotelMT))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Khách Sạn Miền Trung</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($hotelMT as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>{{ $h->name ?? $h->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($hotelMN))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Khách Sạn Miền Nam</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($hotelMN as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>{{ $h->name ?? $h->seo->title ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>
                
                <li>
                    <div onclick="showHideListMenuMobile(this);">
                        <i class="fa-solid fa-book"></i>
                        <span class="nav-mobile_main__title">Cẩm Nang Du Lịch</span>
                        <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                    </div>
                    <ul style="display:none;">
                        @if(!empty($guideMB))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Cảm Nang Miền Bắc</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($guideMB as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>Cẩm Nang {{ $h->display_name ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($guideMT))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Cẩm Nang Miền Trung</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($guideMT as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>Cẩm Nang {{ $h->display_name ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                        @if(!empty($guideMN))
                            <li>
                                <div onclick="showHideListMenuMobile(this);">
                                    <span class="nav-mobile_main__title">Cẩm Nang Miền Nam</span>
                                    <span class="nav-mobile_main__arrow"><i class="fas fa-chevron-right"></i></span>
                                </div>
                                <ul style="display:none;">
                                @foreach($guideMN as $h)
                                    <li>
                                        <a href="/{{ $h->seo->slug_full ?? null }}" title="{{ $h->name ?? $h->seo->title ?? null }}">
                                            <div>Cẩm Nang {{ $h->display_name ?? null }}</div>
                                        </a>
                                    </li>
                                @endforeach
                                </ul>
                            </li>
                        @endif
                    </ul>
                </li>

                <li>
                    <a href="/lien-he-hitour" title="Liên hệ {{ config('main_'.env('APP_NAME').'.company_name') }}">
                        <i class="fa-solid fa-phone"></i>
                        <span class="nav-mobile_main__title">Liên Hệ</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- END:: Menu Mobile -->