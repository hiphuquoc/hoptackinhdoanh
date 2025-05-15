<!-- Lọc theo tags-->
@if(!empty($exchangeTags)&&$exchangeTags->isNotEmpty())
    @foreach($exchangeTags as $key => $tags)
        @php
            $filterName = config('main_'.env('APP_NAME').'.filter.'.$key.'.name');
            // dd($tags);
        @endphp
        <div class="sidebarBox">
            <div class="sidebarBox_title">
                Lọc theo {{ $filterName }}
            </div>
            <div class="sidebarBox_box">
                <div class="sidebarBox_box_tags">
                    @foreach($tags as $tag)
                        @if(!empty($tag->seo->title))
                            <a href="/{{ $tag->seo->slug_full }}" class="sidebarBox_box_tags_item">
                                {{ $tag->seo->title }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
@endif
<!-- Dịch vụ-->
<div class="sidebarBox">
    <div class="sidebarBox_title">
        Dịch vụ có thể bạn cần?
    </div>
    <div class="sidebarBox_box">
        <ul class="listServices">
            <li>
                <a href="#" title="Liên hệ {{ config('main_'.env('APP_NAME').'.company_name') }}" class="listMenuGroup">
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
            <li>
                <a href="#" title="Liên hệ {{ config('main_'.env('APP_NAME').'.company_name') }}" class="listMenuGroup">
                    <div class="listMenuGroup_icon">
                        <svg><use xlink:href="#icon_land"></use></svg>
                    </div>
                    <div class="listMenuGroup_content">
                        <div class="listMenuGroup_content_title">
                            <div>Dự án bất động sản</div>
                            <div class="listMenuGroup_content_title_tag">mới</div>
                        </div>
                        <div class="listMenuGroup_content_description">Cơ hội đầu tư bất động sản hấp dẫn, kết nối với các dự án uy tín và tiềm năng</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" title="Liên hệ {{ config('main_'.env('APP_NAME').'.company_name') }}" class="listMenuGroup">
                    <div class="listMenuGroup_icon">
                        <svg><use xlink:href="#icon_chart"></use></svg>
                    </div>
                    <div class="listMenuGroup_content">
                        <div class="listMenuGroup_content_title">
                            <div>Nhượng quyền</div>
                            <div class="listMenuGroup_content_title_tag">mới</div>
                        </div>
                        <div class="listMenuGroup_content_description">Khởi nghiệp dễ dàng với cơ hội nhượng quyền từ các thương hiệu uy tín</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" title="Liên hệ {{ config('main_'.env('APP_NAME').'.company_name') }}" class="listMenuGroup">
                    <div class="listMenuGroup_icon">
                        <svg><use xlink:href="#icon_handshake"></use></svg>
                    </div>
                    <div class="listMenuGroup_content">
                        <div class="listMenuGroup_content_title">
                            <div>Đối tác</div>
                        </div>
                        <div class="listMenuGroup_content_description">Kết nối đối tác toàn quốc, tìm nguồn hàng chất lượng để tối ưu hóa kinh doanh</div>
                    </div>
                </a>
            </li>
            <li>
                <a href="#" title="Liên hệ {{ config('main_'.env('APP_NAME').'.company_name') }}" class="listMenuGroup">
                    <div class="listMenuGroup_icon">
                        <svg><use xlink:href="#icon_wallet"></use></svg>
                    </div>
                    <div class="listMenuGroup_content">
                        <div class="listMenuGroup_content_title">
                            <div>Quỹ đầu tư</div>
                            <div class="listMenuGroup_content_title_tag">mới</div>
                        </div>
                        <div class="listMenuGroup_content_description">Các quỹ đầu tư uy tín, hỗ trợ khởi nghiệp và thúc đẩy tăng trưởng bền vững</div>
                    </div>
                </a>
            </li>
        </ul>

    </div>
</div>