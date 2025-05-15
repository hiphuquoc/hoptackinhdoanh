<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;
// use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use App\Helpers\Url;
use App\Http\Controllers\CategoryMoneyController;
use App\Models\Blog;
use App\Models\Category;
// use App\Models\Tag;
// use App\Models\Style;
use App\Models\Customer;
use App\Models\Page;
use App\Models\CategoryBlog;
use App\Models\ExchangeTag;
use Illuminate\Support\Facades\Auth;


class RoutingController extends Controller{

    public function routing(Request $request) {
        // 1. Xử lý đường dẫn và giải mã URL
        $slug = $request->path();
        $decodedSlug = urldecode($slug);
        $tmpSlug = explode('/', $decodedSlug);
    
        // Loại bỏ phần tử rỗng và các phần không cần thiết (ví dụ: 'public')
        $arraySlug = array_filter($tmpSlug, function ($part) {
            return !empty($part) && $part !== 'public';
        });
    
        // Loại bỏ hashtag và query string từ phần cuối cùng của đường dẫn
        $arraySlug[count($arraySlug) - 1] = preg_replace('#([\?|\#]+).*$#imsU', '', end($arraySlug));
        $urlRequest = implode('/', $arraySlug);
    
        // 2. Kiểm tra xem URL có tồn tại trong cơ sở dữ liệu không
        $itemSeo = Url::checkUrlExists(end($arraySlug));
    
        // Nếu URL không khớp, redirect về URL chính xác
        if (!empty($itemSeo->slug_full) && $itemSeo->slug_full !== $urlRequest) {
            return Redirect::to($itemSeo->slug_full, 301);
        }
    
        // 3. Nếu URL hợp lệ, xử lý dữ liệu
        if (!empty($itemSeo->type)) {
            // Thiết lập ngôn ngữ và cấu hình theo IP
            $language = $itemSeo->language;
            SettingController::settingLanguage($language);
    
            // Xử lý tham số tìm kiếm và chế độ hiển thị (view mode)
            $search = request('search') ?? null;
            $paramsSlug = [];
            if (!empty($search)) $paramsSlug['search'] = $search;
            
            // Tạo key và đường dẫn cache
            $appName        = env('APP_NAME');
            $cacheKey   = self::buildNameCache($itemSeo->slug_full, $paramsSlug);
            $cacheName = $cacheKey . '.' . config("main_" . $appName . ".cache.extension");
            $cacheFolder = config("main_" . $appName . ".cache.folderSave");
            $cachePath = $cacheFolder . $cacheName;
            $cdnDomain = config("main_" . $appName . ".google_cloud_storage.cdn_domain");
    

            $disk       = Storage::disk(config("main_{$appName}.cache.disk"));
            $useCache   = env('APP_CACHE_HTML', true);
            $redisTtl   = config('app.cache_redis_time', 86400);     // Redis: 1 ngày
            $fileTtl    = config('app.cache_html_time', 2592000);     // GCS: 30 ngày
    
            $htmlContent = null;
    
            // 4. Thử lấy từ Redis
            if ($useCache && Cache::has($cacheKey)) {
                $htmlContent = Cache::get($cacheKey);
            }
    
            // 5. Nếu không có Redis → thử từ GCS (qua CDN)
            if ($useCache && !$htmlContent && $disk->exists($cachePath)) {
                $lastModified = $disk->lastModified($cachePath);
                if ((time() - $lastModified) < $fileTtl) {
                    $htmlContent = @file_get_contents($cdnDomain . $cachePath);
                    if ($htmlContent) {
                        Cache::put($cacheKey, $htmlContent, $redisTtl);
                    }
                }
            }
    
            // 6. Nếu không có cache → Render
            if (!$htmlContent) {
                // Lấy dữ liệu thông qua hàm fetchDataForRouting
                $htmlContent = $this->fetchDataForRouting($itemSeo, $language);
    
                if (!$htmlContent) {
                    return \App\Http\Controllers\ErrorController::error404();
                }
    
                // Lưu cache lại nếu bật
                if ($useCache) {
                    Cache::put($cacheKey, $htmlContent, $redisTtl);
                    $disk->put($cachePath, $htmlContent);
                }
            }
    
            echo $htmlContent;
        } else {
            return \App\Http\Controllers\ErrorController::error404();
        }
    }

    // Hàm hỗ trợ để lấy dữ liệu cho routing
    private function fetchDataForRouting($itemSeo, $language) {
        // Breadcrumb
        $breadcrumb = Url::buildBreadcrumb($itemSeo->slug_full);
    
        // Thông tin cơ bản
        $modelName = config('tablemysql.' . $itemSeo->type . '.model_name');
        $modelInstance = resolve("\App\Models\\$modelName");
        $idSeo = $itemSeo->id;
    
        // Lấy dữ liệu chính
        $item = $modelInstance::select('*')
            ->whereHas('seos', function ($query) use ($idSeo) {
                $query->where('seo_id', $idSeo);
            })
            ->with('seo', 'seos')
            ->first();
    
        if (!$item) {
            return null; // Không tìm thấy dữ liệu
        }
    
        // Xử lý theo từng loại type
        switch ($itemSeo->type) {
            case 'tag_info':
                return $this->handlePageInfo($item, $itemSeo, $language, $breadcrumb);
    
            case 'page_info':
                return $this->handlePageInfo($item, $itemSeo, $language, $breadcrumb);
    
            case 'category_blog':
                return $this->handleCategoryBlog($item, $itemSeo, $language, $breadcrumb);
    
            case 'blog_info':
                return $this->handleBlogInfo($item, $itemSeo, $language, $breadcrumb);
            
            case 'exchange_info':
                return $this->handleExchangeInfo($item, $itemSeo, $language, $breadcrumb);
    
            default:
                foreach (config('main_' . env('APP_NAME') . '.category_type') as $type) {
                    if ($itemSeo->type === $type['key']) {
                        return $this->handleCategoryType($item, $itemSeo, $language, $breadcrumb);
                    }
                }
                break;
        }
    
        return null; // Trường hợp không khớp type nào
    }

    private function handlePageInfo($item, $itemSeo, $language, $breadcrumb) {
        $dataContent    = CategoryMoneyController::buildTocContentMain($itemSeo->contents, $language);
        $services       = Category::select('*')
                            ->with('seo', 'seos', 'tags')
                            ->get();
        return view('main.page.index', compact(
            'item', 'itemSeo', 'services', 'dataContent', 'language', 'breadcrumb'
        ))->render();
    }

    private function handleCategoryBlog($item, $itemSeo, $language, $breadcrumb) {
        $params = [
            'sort_by' => Cookie::get('sort_by') ?? null,
            'array_category_blog_id' => CategoryBlog::getTreeCategoryByInfoCategory($item, [])->pluck('id')->prepend($item->id)->toArray(),
        ];
    
        $blogs = \App\Http\Controllers\CategoryBlogController::getBlogs($params, $language)['blogs'];
        $blogFeatured = BlogController::getBlogFeatured($language);
    
        return view('main.categoryBlog.index', compact(
            'item', 'itemSeo', 'blogs', 'blogFeatured', 'language', 'breadcrumb'
        ))->render();
    }

    private function handleBlogInfo($item, $itemSeo, $language, $breadcrumb) {
        $blogFeatured = BlogController::getBlogFeatured($language);
        $dataContent = CategoryMoneyController::buildTocContentMain($itemSeo->contents, $language);
        $htmlContent = str_replace('<div id="tocContentMain"></div>', '<div id="tocContentMain">' . $dataContent['toc_content'] . '</div>', $dataContent['content']);
    
        return view('main.blog.index', compact(
            'item', 'itemSeo', 'blogFeatured', 'language', 'breadcrumb', 'htmlContent'
        ))->render();
    }

    private function handleCategoryType($item, $itemSeo, $language, $breadcrumb) {
        $flagFree = in_array($itemSeo->slug, config('main_' . env('APP_NAME') . '.url_free_wallpaper_category'));
        if ($flagFree) {
            return $this->handleFreeCategory($item, $itemSeo, $language, $breadcrumb);
        }
    
        return $this->handlePaidCategory($item, $itemSeo, $language, $breadcrumb);
    }
    
    private function handleExchangeInfo($item, $itemSeo, $language, $breadcrumb) {
        $idExchange     = $item->id;
        $exchangeTags   = ExchangeTag::with('seo') // assuming relation is defined
                            ->whereHas('exchanges', function($query) use($idExchange){
                                $query->where('exchange_info_id', $idExchange);
                            })
                            ->get()
                            ->groupBy('type_filter');
        return view('main.exchange.index', compact(
            'item', 'itemSeo', 'exchangeTags', 'language', 'breadcrumb'
        ))->render();
    }
    
    private function handlePaidCategory($item, $itemSeo, $language, $breadcrumb) {
        // Khởi tạo các tham số tìm kiếm
        $arrayIdCategory    = Category::getArrayIdCategoryRelatedByIdCategory($item, [$item->id]);
        $viewBy             = request()->cookie('view_by') ?? 'each_set';
        $search             = request('search') ?? null;
        $params = [
            'array_category_info_id' => $arrayIdCategory,
            'view_by' => $viewBy,
            'filters' => request()->get('filters') ?? [],
            'loaded' => 0,
            'request_load' => 10,
            'sort_by' => Cookie::get('sort_by') ?? null,
            'search' => $search,
        ];
    
        // Lấy wallpapers từ controller
        $response = CategoryMoneyController::getWallpapers($params, $language);
    
        // Đảm bảo biến wallpapers luôn tồn tại
        $wallpapers = $response['wallpapers'] ?? [];
        $total = $response['total'] ?? 0;
        $loaded = $response['loaded'] ?? 0;
    
        // Xây dựng toc_content
        $dataContent = CategoryMoneyController::buildTocContentMain($itemSeo->contents, $language);
    
        // Render view
        return view('main.categoryMoney.index', compact(
            'item', 'itemSeo', 'breadcrumb', 'wallpapers', 'arrayIdCategory', 'total', 'loaded', 'language', 'viewBy', 'search', 'dataContent'
        ))->render();
    }
    
    public static function buildNameCache($slugFull, $params = []){
        $response     = '';
        if(!empty($slugFull)){
             /* xây dựng  slug */
             $tmp    = explode('/', $slugFull);
             $result = [];
             foreach($tmp as $t) if(!empty($t)) $result[] = $t;
             $response = implode('-', $result);
            /* duyệt params để lấy prefix hay # */
            if(!empty($params)){
                $part   = '';
                foreach($params as $key => $param) $part .= $key.'-'.$param;
                if(!empty($part)) $response = $response.'-'.$part;
            }
        }
        return $response;
    }
    
}
