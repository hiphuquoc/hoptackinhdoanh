@extends('layouts.main')
@push('cssFirstView')
    <!-- trường hợp là local thì dùng vite để chạy npm run dev lúc code -->
    @if(env('APP_ENV')=='local')
        @vite('resources/sources/main/home-first-view.scss')
    @else
        @php
            $manifest           = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            $cssFirstView       = $manifest['resources/sources/main/home-first-view.scss']['file'];
        @endphp
        <style type="text/css">
            {!! file_get_contents(asset('build/' . $cssFirstView)) !!}
        </style>
    @endif
@endpush
@push('headCustom')
<!-- ===== START:: SCHEMA ===== -->
    <!-- STRAT:: Organization Schema -->
    @include('main.schema.organization')
    <!-- END:: Organization Schema -->

    <!-- STRAT:: Article Schema -->
    @include('main.schema.article', compact('item'))
    <!-- END:: Article Schema -->

    <!-- STRAT:: Article Schema -->
    @include('main.schema.creativeworkseries', compact('item'))
    <!-- END:: Article Schema -->
    
    <!-- STRAT:: FAQ Schema -->
    @include('main.schema.itemlist', ['data' => $categories])
    <!-- END:: FAQ Schema -->

    <!-- STRAT:: Title - Description - Social -->
    @include('main.schema.social', ['item' => $item, 'lowPrice' => 1, 'highPrice' => 5])
    <!-- END:: Title - Description - Social -->

    <!-- STRAT:: FAQ Schema -->
    @include('main.schema.faq', ['data' => $item->faqs])
    <!-- END:: FAQ Schema -->
<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    {{-- @include('main.form.sortBooking', [
        'item'      => $item,
        'active'    => 'tour'
    ]) --}}

    @include('main.template.search')

    @include('main.snippets.breadcrumb')

    <div class="breadcrumbBox">
    <div class="container maxLine_1">
        <a href="/vi" title="Trang chủ" class="breadcrumbBox_home" aria-label="Trang chủ">Trang chủ</a>
        <svg><use xlink:href="#icon_arrow_right"></use></svg>
        <span>Dự án đang gọi vốn - tìm người đồng hành</span>
        </div>
    </div>

    <div class="pageContent">
        <div class="layoutPageCategoryBlog container">
            <div class="layoutPageCategoryBlog_main">
                
                <!-- bussiness box -->
                <div class="sectionBox">
                    <h1 class="titlePage">Wallsora - Dự án hình nền Premium với hệ thống CRM & affiliate hoàn chỉnh, 50 ngôn ngữ</h1>
                    <!-- subtitle -->
                    <div class="businessPlanDetailBox">
                        <div class="businessPlanDetailBox_subTitle">
                            <div class="businessPlanDetailBox_subTitle_item">
                                <svg><use xlink:href="#icon_eye_bold"></use></svg>
                                <div>lượt xem 2k</div>
                            </div>
                            <div class="businessPlanDetailBox_subTitle_item">
                                <svg><use xlink:href="#icon_calendar_days"></use></svg>
                                <div>đăng 10/05/2025</div>
                            </div>
                            <div class="businessPlanDetailBox_subTitle_item">
                                <svg><use xlink:href="#icon_calendar_xmark"></use></svg>
                                <div>hết hạn 31/08/2025</div>
                            </div>
                        </div>
                        <div class="businessPlanDetailBox_gallery">
                            <!-- gallery box -->
                            @include('main.home.gallery')
                        </div>
                        <div class="businessPlanDetailBox_contentBox">

                            @include('main.home.test')
                            
                        </div>
                    </div>
                    
                </div>

            </div>
            <div class="layoutPageCategoryBlog_sidebar">
                @include('main.home.sideBar')
            </div>
        </div>
    </div>
    
@endsection
@push('modal')
    {{-- <!-- Message Add to Cart -->
    <div id="js_addToCart_idWrite">
        @include('main.cart.cartMessage', [
            'title'     => '',
            'option'    => null,
            'quantity'  => 0,
            'price'     => 0,
            'image'     => null,
            'language'  => $language
        ])
    </div> --}}
@endpush
@push('bottom')
    <!-- Header bottom -->
    {{-- @include('main.snippets.headerBottom') --}}
    <!-- === START:: Zalo Ring === -->
    {{-- @include('main.snippets.zaloRing') --}}
    <!-- === END:: Zalo Ring === -->
@endpush
@push('scriptCustom')
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            let interval; // Biến lưu trữ interval cho carousel

            // Sử dụng $(document).on để xử lý sự kiện trên các phần tử động
            $(document).on('mouseenter', '.categoryGrid_box_item_image', function() {
                var $children = $(this).children('img');
                let currentIndex = 0;

                // Đổi ảnh ngay lập tức khi hover lần đầu
                $children.eq(currentIndex).removeClass('active');
                currentIndex = (currentIndex + 1) % $children.length;
                $children.eq(currentIndex).addClass('active');

                // Khởi động carousel để tiếp tục đổi ảnh mỗi 2s
                interval = setInterval(function() {
                    $children.eq(currentIndex).removeClass('active');
                    currentIndex = (currentIndex + 1) % $children.length; // Quay lại từ đầu khi hết phần tử
                    $children.eq(currentIndex).addClass('active');
                }, 2000); // Thay đổi thời gian giữa các vòng lặp nếu cần

            }).on('mouseleave', '.categoryGrid_box_item_image', function() {
                clearInterval(interval); // Dừng carousel khi chuột ra ngoài
                var $children = $(this).children('img');
                $children.removeClass('active'); // Ẩn tất cả hình ảnh khi không hover
                $children.eq(0).addClass('active'); // Hiển thị hình ảnh đầu tiên khi không hover
            });

            // Khởi động carousel với hình ảnh đầu tiên khi trang tải hoặc khi có phần tử mới được thêm vào
            function initializeImages() {
                $('.categoryGrid_box_item_image').each(function() {
                    var $children = $(this).children('img');
                    $children.eq(0).addClass('active'); // Hiển thị hình ảnh đầu tiên
                });
            }

            // Khởi động cho các phần tử ban đầu
            initializeImages();
        });
    </script>
@endpush
