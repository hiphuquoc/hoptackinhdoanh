<!-- Dịch vụ-->
@if(!empty($services)&&$services->isNotEmpty())
    @foreach($services as $serviceGroup)
        @foreach($serviceGroup->seos as $seo)
            @if(!empty($seo->infoSeo->language)&&$seo->infoSeo->language==$language)
                <div class="sidebarBox">
                    <div class="sidebarBox_title">
                        Dịch vụ {{ $seo->infoSeo->title ?? null }}
                    </div>
                    <div class="sidebarBox_box">
                        <ul class="listServices">
                            @foreach($serviceGroup->tags as $serviceDetail)
                                {{-- @foreach($serviceDetail->seos as $s)
                                    @if(!empty($s->infoSeo->language)&&$s->infoSeo->language==$language)
                                        @php
                                            $title = $s->infoSeo->title ?? null;
                                        @endphp
                                        <li>
                                            <a href="/{{ $s->infoSeo->slug_full }}" title="{{ $title }}" class="listMenuGroup">
                                                <div class="listMenuGroup_icon">
                                                    <svg><use xlink:href="#icon_chart"></use></svg>
                                                </div>
                                                <div class="listMenuGroup_content">
                                                    <div class="listMenuGroup_content_title">
                                                        <div>Dự án kinh doanh</div>
                                                    </div>
                                                    <div class="listMenuGroup_content_description">Các dự án đang gọi vốn, tìm người đồng hành phù hợp để cùng phát triển ý tưởng</div>
                                                </div>
                                            </a>
                                        </li>
                                        @include('main.snippets.itemMenu', [

                                        ])
                                        @break
                                    @endif
                                @endforeach --}}
                                @include('main.snippets.itemMenu', [
                                    'itemMenu'  => $serviceDetail->infoTag,
                                ])

                            @endforeach
                        </ul>

                    </div>
                </div>
                @break
            @endif
        @endforeach
    @endforeach
@endif