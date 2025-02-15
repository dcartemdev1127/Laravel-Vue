
<aside id="admin-layout-menu" class="d-none d-sm-flex w-full h-[calc(100vh-3rem)] flex flex-col bg-white rounded-2xl shadow-xl">
    <div class="p-4 mb-2">
        <a class="block"><img src="{{ asset('/images/background/watermark.png') }}" class="m-auto"/></a>
    </div>
    <nav class="flex flex-col gap-1 p-2 text-base font-normal">
        @foreach($menuData->menu as $menuItem)
            <div class="relative block w-full nav-container {{ str_contains($currentRouteName, $menuItem->slug) ? 'active' : '' }}">
                <a href="{{ isset($menuItem->url) ? url('/admin/' . $menuItem->url): 'javascript:void(0);' }}" type="button" class="nav-item flex items-center justify-between w-full p-3 text-xl antialiased leading-snug text-left transition-colors rounded-lg">
                    <div class="grid mr-4 place-items-center">
                        <i class="mdi {{ $menuItem->icon }}"></i>
                    </div>
                    <p class="block mr-auto text-base font-bold antialiased font-normal leading-relaxed text-blue-gray-900">
                        {{ $menuItem->name }}
                    </p>
                    @if(isset($menuItem->submenu))
                        <span class="ml-4">
                            <i class="chevron-icon {{ str_contains($currentRouteName, $menuItem->slug) ? 'mdi mdi-chevron-down' : 'mdi mdi-chevron-left' }}"></i>
                        </span>
                    @endif
                </a>
                @if(isset($menuItem->submenu))
                    <div class="sub-menu">
                        <div class="block w-full py-1 text-sm antialiased font-light leading-normal">
                            <nav class="flex flex-col gap-1 p-0 text-base font-normal">
                                @foreach($menuItem->submenu as $subItem)
                                    <a href="{{ isset($subItem->url) ? url('/admin/' . $subItem->url) : 'javascript:void(0);' }}" class="sub-menu-item {{ str_contains($currentRouteName, $subItem->slug) ? 'active' : '' }} flex items-center w-[calc(100% - 8px)] ml-2 p-2 leading-tight rounded-lg outline-none text-start">
                                        <div class="grid mx-4 place-items-center">
                                            <i class="mdi {{$subItem->icon}}"></i>
                                        </div>
                                        {{ $subItem->name }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach
    </nav>
</aside>