@php
    $user = Auth::user();
    $menuData = json_decode(file_get_contents(storage_path('app/menus/adminMenu.json')));
@endphp

<header class="absolute w-full bg-white rounded-[12px] p-2">
    <dashboard-header-component
        :user = "{{ json_encode($user) }}"
        :menu-data = "{{ json_encode($menuData) }}"
        :current-route-name = "{{ json_encode($currentRouteName) }}"
    />
</header>