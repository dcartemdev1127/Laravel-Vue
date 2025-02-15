<header class="absolute w-full">
    <nav class="border-gray-200 px-4 lg:px-6 py-2.5">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" class="mr-3 mt-1 h-[72px] md:min-h-[72px] lg:min-h-[80px]" alt="Find A Super Tutor" />
            </a>
            @if(!Auth::check())
            <div class="flex items-center lg:order-2">
                <a href="/register" class="rounded-3xl bg-btnbg text-cardbg hover:bg-blue-800 focus:ring-1 focus:ring-blue-300 font-light text-sm px-4 py-3 lg:px-5 lg:py-2.5 mr-2 focus:outline-none hidden md:block">Create an Account</a>
                <a href="/login" class="rounded-3xl text-gray-800 hover:bg-gray-50 focus:ring-1 focus:ring-gray-300 font-light text-sm px-4 lg:px-5 py-3 lg:py-2.5 mr-2 focus:outline-none hidden md:block">Log In</a>
                <button type="button" onclick="openNav()" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden md:hidden hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-gray-200">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            @else
            <div class="flex items-center lg:order-2">
                <a href="/dashboard" class="rounded-3xl bg-btnbg text-cardbg hover:bg-blue-800 focus:ring-1 focus:ring-blue-300 font-light text-sm px-4 py-3 lg:px-5 lg:py-2.5 mr-2 focus:outline-none">Dashboard</a>
                <a href="/logout" class="rounded-3xl text-gray-800 hover:bg-gray-50 focus:ring-1 focus:ring-gray-300 font-light text-sm px-4 lg:px-5 py-3 lg:py-2.5 mr-2 focus:outline-none hidden md:block">Logout</a>
            </div>
            @endif
            <div class="h-full fixed z-50 top-0 left-0 overflow-x-hidden backdrop-blur-md duration-100" id="mobile-menu" style="width:0;">
                <div class="relative">
                    <button class="w-16 h-16 border shadow absolute right-2 top-6 rounded-full" onclick="closeNav()">
                        <img src="/images/icons/close.svg" class="m-auto"/>
                    </button>
                </div>
                @if(!Auth::check())
                <ul class="flex flex-col gap-2 mt-40 mb-4">
                    <li class="text-[20px] text-center">
                        <a href="/register" class="rounded-3xl block bg-btnbg m-auto text-cardbg hover:bg-blue-800 focus:ring-1 focus:ring-blue-300 font-light text-[20px] py-3 mx-6 focus:outline-none">
                            Create an Account
                        </a>
                    </li>
                    <li class="text-[20px] text-center">
                        <a href="/login" class="rounded-3xl block bg-[#0000001f] mt-3 focus:ring-1 focus:ring-gray-300 font-light text-[20px] py-3 mx-6 focus:outline-none">Log In</a>
                    </li>
                </ul>
                @else
                <ul class="flex flex-col gap-2 mt-40 mb-4">
                    <li class="text-[20px] text-center">
                        <a href="/dashboard" class="rounded-3xl block bg-btnbg m-auto text-cardbg hover:bg-blue-800 focus:ring-1 focus:ring-blue-300 font-light text-[20px] py-3 mx-6 focus:outline-none">
                            Dashboard
                        </a>
                    </li>
                    <li class="text-[20px] text-center">
                        <a href="/logout" class="rounded-3xl block bg-[#0000001f] mt-3 focus:ring-1 focus:ring-gray-300 font-light text-[20px] py-3 mx-6 focus:outline-none">Logout</a>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </nav>
</header>