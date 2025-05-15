@php
   $categories    = \App\Models\Category::select('*')
                     ->with('seo', 'seos', 'tags.infoTag')
                     ->get();
   $xhtmlBodyMenu = '';
@endphp     

<div class="megaMenu">
   <div class="megaMenu_title">
      <ul>
            @php
                $i = 0;
            @endphp
            @foreach($categories as $category)
                  @foreach($category->seos as $seo)
                        @if(!empty($seo->infoSeo->language)&&$seo->infoSeo->language==$language)
                              @php
                                    $classSelected = $i==0 ? 'selected' : null;
                                    ++$i;
                                    // tiện foreach lấy luôn dữ liệu con
                                    $xhtmlBodyMenu .= '<ul data-menu="menu_'.$category->id.'">';
                                    foreach($category->tags as $tag){
                                          $xhtmlBodyMenu .= view('main.snippets.itemMenu', [
                                                'itemMenu'  => $tag->infoTag,
                                                'language'  => $language,
                                          ])->render();
                                    }
                                    $xhtmlBodyMenu .= '</ul>';
                              @endphp
                              <li id="menu_{{ $category->id ?? null }}" onmouseover="openMegaMenu(this.id);" class="{{ $classSelected }}">
                                    <div>{{ $seo->infoSeo->title ?? null }}</div>
                              </li>
                              @break
                        @endif
                  @endforeach
            @endforeach
      </ul>
   </div>
   <div class="megaMenu_content">
      {!! $xhtmlBodyMenu !!}
   </div>
</div>
@pushonce('scriptCustom')
   <script type="text/javascript">
      function openMegaMenu(id){
        var elemt	= $('#'+id);
        elemt.siblings().removeClass('selected');
        elemt.addClass('selected');
        $('[data-menu]').each(function(){
            var key	= $(this).attr('data-menu');
            if(key==id){
            $(this).css('display', 'grid');
            }else {
                $(this).css('display', 'none');
            }
        });
      }
   </script>
@endpushonce