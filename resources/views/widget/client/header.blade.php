@if ($menu->count() > 0) 
<header class="w-full bg-white sticky top-0 z-[9999] shadow-sm font-sans"> 
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"> 
        <div class="flex justify-between items-center h-16">
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ $meta->logo }}" alt="{{ $meta->web_name }}" class="h-10 w-auto">
                </a>
            </div>

            <nav class="hidden md:flex space-x-8 items-center">
                @php
                    $parents = $menu->where('type_1', 'parent');
                    $menuItems = $menu->groupBy('parent_id');
                @endphp

                @foreach ($parents as $parent)
                    @php
                        $submenus = $menuItems[$parent->id] ?? collect();
                        $hasSubmenus = $submenus->count() > 0;
                    @endphp

                    <a href="{{ $parent->type_2 == 'page' ? url('page/' . $parent->slug) : url($parent->slug) }}"
                        data-target="dropdown{{ $parent->id }}"
                        class="dropdown-btn group inline-flex items-center text-sm font-semibold text-gray-700 hover:text-black transition-colors {{ $hasSubmenus ? 'parent-link' : '' }}">
                        <span>{{ $parent->name }}</span>
                        @if ($hasSubmenus)
                            <svg class="ml-1 h-4 w-4 text-gray-400 group-hover:text-black transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="hidden md:flex items-center">
                @auth
                    <a href="/portal/login" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-sm font-medium rounded-full transition-all">
                        {{ auth()->user()->name }}
                        <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user-icon lucide-circle-user ml-1"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="10" r="3"/><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/></svg>
                    </a>
                @else
                    <a href="/portal/login" class="inline-flex items-center justify-center px-5 py-2 border border-transparent text-sm font-medium rounded-full transition-all">
                        Sign in
                        <svg xmlns="[http://www.w3.org/2000/svg](http://www.w3.org/2000/svg)" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-1 lucide lucide-log-in-icon lucide-log-in"><path d="m10 17 5-5-5-5"/><path d="M15 12H3"/><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/></svg>
                    </a>
                @endauth
            </div>

            <div class="flex md:hidden items-center">
                <button id="mobile-menu-btn" type="button" class="text-gray-700 hover:text-black focus:outline-none p-2">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100 absolute w-full left-0 shadow-lg h-screen overflow-y-auto pb-20">
        <div class="px-4 pt-2 pb-6 space-y-1">
            @foreach ($parents as $parent)
                @php
                    $submenus = $menuItems[$parent->id] ?? collect();
                    $hasSubmenus = $submenus->count() > 0;
                @endphp

                @if ($hasSubmenus)
                    <div class="border-b border-gray-50 last:border-0">
                        <div class="flex items-center justify-between pr-2">
                            <a href="{{ $parent->type_2 == 'page' ? url('page/' . $parent->slug) : url($parent->slug) }}" 
                               class="flex-1 block px-3 py-3 text-base font-medium text-gray-700 hover:text-black hover:bg-gray-50 rounded-l-md">
                                {{ $parent->name }}
                            </a>
                            <button type="button" 
                                    data-target="mobile-sub-{{ $parent->id }}"
                                    class="mobile-submenu-toggle p-3 text-gray-500 hover:text-black hover:bg-gray-50 rounded-r-md focus:outline-none">
                                <svg class="w-5 h-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </div>
                        <div id="mobile-sub-{{ $parent->id }}" class="hidden bg-gray-50 rounded-md mb-2 ml-3">
                            @foreach ($submenus as $submenu)
                                <a href="{{ $submenu->type_2 == 'page' ? url('page/' . $submenu->slug) : url($submenu->slug) }}"
                                   class="block pl-4 pr-3 py-2.5 text-sm font-medium text-gray-600 hover:text-black hover:bg-gray-100 rounded-md">
                                    {{ $submenu->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $parent->type_2 == 'page' ? url('page/' . $parent->slug) : url($parent->slug) }}" 
                       class="block px-3 py-3 text-base font-medium text-gray-700 hover:text-black hover:bg-gray-50 rounded-md border-b border-gray-50 last:border-0">
                        {{ $parent->name }}
                    </a>
                @endif
            @endforeach

            <div class="pt-4 mt-4 border-t border-gray-100">
                <a href="/portal/login" class="flex items-center justify-center w-full px-4 py-3 text-base font-medium text-white bg-gray-900 rounded-md hover:bg-gray-800">
                    @auth
                        Dashboard
                    @else
                        Sign in
                    @endauth
                </a>
            </div>
        </div>
    </div>

    @foreach ($parents as $parent)
        @php
            $submenus = $menuItems[$parent->id] ?? collect();
        @endphp

        @if ($submenus->count())
            <div id="dropdown{{ $parent->id }}" class="hidden absolute bg-white shadow rounded w-56 z-50 border border-gray-100 mt-2 py-2">
                @foreach ($submenus as $submenu)
                    <a href="{{ $submenu->type_2 == 'page' ? url('page/' . $submenu->slug) : url($submenu->slug) }}"
                        class="block px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-black transition-colors">
                        {{ $submenu->name }} 
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach
</header>

@push('scripts')
    <script>
        const dropdownButtons = document.querySelectorAll('.dropdown-btn');
        const dropdownMenus = document.querySelectorAll('[id^="dropdown"]');
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileSubmenuToggles = document.querySelectorAll('.mobile-submenu-toggle');

        if(mobileMenuBtn){
            mobileMenuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        mobileSubmenuToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = toggle.getAttribute('data-target');
                const targetEl = document.getElementById(targetId);
                const icon = toggle.querySelector('svg');
                
                if(targetEl) {
                    targetEl.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                }
            });
        });

        dropdownButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.stopPropagation();
                const target = button.getAttribute('data-target');

                dropdownMenus.forEach(menu => {
                    if (menu.id === target) {
                        menu.classList.toggle('hidden');
                        const rect = button.getBoundingClientRect();
                        menu.style.left = rect.left + 'px'; 
                    } else {
                        menu.classList.add('hidden');
                    }
                });
            });
        });

        window.addEventListener('click', () => {
            dropdownMenus.forEach(menu => menu.classList.add('hidden'));
        });

        document.addEventListener('DOMContentLoaded', function() {
            const parentLinks = document.querySelectorAll('.parent-link');

            parentLinks.forEach(link => {
                let clickCount = 0;
                let timeout;

                link.addEventListener('click', function(e) {
                    clickCount++;

                    if (clickCount === 1) {
                        e.preventDefault();
                        timeout = setTimeout(() => {
                            clickCount = 0;
                        }, 300);
                    } else if (clickCount === 2) {
                        e.preventDefault();
                        clearTimeout(timeout);
                        clickCount = 0;
                        window.location.href = this.getAttribute('href');
                    }
                });
            });
        });
    </script>
@endpush
@endif