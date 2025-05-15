@if(!empty($itemMenu->seos)&&$itemMenu->seos->isNotEmpty())
    @foreach($itemMenu->seos as $seo)
        @if(!empty($seo->infoSeo->language)&&$seo->infoSeo->language==$language)
            @php
                $title          = $seo->infoSeo->title ?? null;
                $description    = $seo->infoSeo->seo_description ?? null;
                $url            = '/'.$seo->infoSeo->slug_full;
                $icon           = !empty($itemMenu->icon) ? '<div class="listMenuGroup_icon"><svg><use xlink:href="#'.$itemMenu->icon.'"></use></svg></div>' : null;
                $sign           = !empty($itemMenu->sign) ? '<div class="listMenuGroup_content_title_tag" style="background:#'.config('main_'.env('APP_NAME').'.sign.'.$itemMenu->sign.'.color').'">'.config('main_'.env('APP_NAME').'.sign.'.$itemMenu->sign.'.name').'</div>' : null;
            @endphp
            <li>
                <a href="{{ $url }}" title="{{ $title }}" class="listMenuGroup">
                    {!! $icon !!}
                    <div class="listMenuGroup_content">
                        <div class="listMenuGroup_content_title">
                            <div>{{ $title }}</div>
                            {!! $sign !!}
                        </div>
                        <div class="listMenuGroup_content_description maxLine_4">{{ $description }}</div>
                    </div>
                </a>
            </li>
            @break
        @endif
    @endforeach
@endif