<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F5F5F0">
    <link href="{{ asset('output.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
</head>
<body class="font-[Poppins] bg-[#F5F5F0]">
    <div class="relative flex flex-col w-full min-h-screen gap-5 mx-auto px-4 md:px-6 lg:px-8">
        <!-- Top Bar -->
        <div id="top-bar" class="flex justify-between items-center mt-[60px]">
            <img src="{{asset('assets/images/logos/logo.svg')}}" class="flex shrink-0" alt="logo">
            <a href="#">
                <img src="{{asset('assets/images/icons/notification.svg')}}" class="w-10 h-10" alt="icon">
            </a>
        </div>

        <!-- Search Form -->
        <form class="flex justify-between items-center w-full">
            <div class="relative flex items-center w-full rounded-l-full px-4 gap-2 bg-white transition-all duration-300 focus-within:ring-2 focus-within:ring-[#FFC700]">
                <img src="{{asset('assets/images/icons/search-normal.svg')}}" class="w-6 h-6" alt="icon">
                <input type="text" class="w-full py-3 appearance-none bg-white outline-none font-semibold placeholder:font-normal placeholder:text-[#878785]" placeholder="Search product...">
            </div>
            <button type="submit" class="h-full rounded-r-full py-3 px-5 bg-[#C5F277]">
                <span class="font-semibold">Explore</span>
            </button>
        </form>

        <!-- Categories Section -->
        <section id="category" class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base md:text-lg font-bold leading-snug">Our Featured <br>Categories</h2>
                <a href="category.html" class="rounded-full px-4 py-1 border border-[#2A2A2A] text-xs leading-[18px]">
                    View All
                </a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @forelse ($categories as $itemCategory)
                    <a href="{{ route('front.category', $itemCategory->slug) }}">
                        <div class="flex items-center justify-between w-full rounded-2xl overflow-hidden bg-white transition-all duration-300 hover:ring-2 hover:ring-[#FFC700]">
                            <div class="flex flex-col gap-[2px] px-[14px]">
                                <h3 class="font-bold text-sm leading-[21px]">{{ $itemCategory->name }}</h3>
                                <p class="text-xs text-[#878785]">{{ $itemCategory->dompets->count() }}</p>
                            </div>
                            <div class="flex shrink-0 w-20 h-[90px] overflow-hidden">
                                <img src="{{ Storage::url($itemCategory->icon) }}" class="w-full h-full object-cover object-left" alt="thumbnail">
                            </div>
                        </div>
                    </a>
                @empty
                    <p>Belum ada data terbaru</p>
                @endforelse
            </div>
        </section>

        <!-- Featured Section -->
        <section id="featured" class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base md:text-lg font-bold leading-snug">Explore Our <br>Featured</h2>
                <a href="#" class="rounded-full px-4 py-1 border border-[#2A2A2A] text-xs leading-[18px]">
                    View All
                </a>
            </div>
            <div class="swiper w-full overflow-hidden">
                <div class="swiper-wrapper">
                    @forelse ($popularDompet as $itemPopularDompet)
                        <div class="swiper-slide !w-fit py-1">
                            <a href="{{ route('front.details', $itemPopularDompet->slug) }}">
                                <div class="flex flex-col w-[230px] rounded-3xl gap-4 p-4 bg-white transition-all duration-300 hover:ring-2 hover:ring-[#FFC700]">
                                    <div class="w-full h-[230px] rounded-3xl bg-[#D9D9D9] overflow-hidden">
                                        <img loading="lazy" src="{{ Storage::url($itemPopularDompet->thumbnail) }}" class="w-full h-full object-cover" alt="thumbnail">
                                    </div>
                                    <div class="flex flex-col gap-2 justify-between">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-sm leading-[20px]">{{ $itemPopularDompet->name }}</h3>
                                            <p class="font-bold text-sm">Rp{{ number_format($itemPopularDompet->price, 0, ',', ',') }}</p>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-1">
                                                <img src="{{asset('assets/images/icons/Star 1.svg')}}" class="w-5 h-5" alt="star">
                                                <p class="font-semibold text-sm">4.5</p>
                                            </div>
                                            <p class="text-sm text-[#878785]">(18,485 reviews)</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @empty
                        <p>Belum ada data terbaru</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Fresh Items -->
        <section id="fresh" class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <h2 class="text-base md:text-lg font-bold leading-snug">Fresh From <br>Great Designers</h2>
                <a href="#" class="rounded-full px-4 py-1 border border-[#2A2A2A] text-xs leading-[18px]">
                    View All
                </a>
            </div>
            <div class="flex flex-col gap-4">
                @forelse ($newDompet as $itemNewDompet)
                    <a href="{{ route('front.details', $itemNewDompet->slug) }}">
                        <div class="flex items-center rounded-3xl p-4 gap-4 bg-white transition-all duration-300 hover:ring-2 hover:ring-[#FFC700]">
                            <div class="w-20 h-20 flex shrink-0 rounded-2xl overflow-hidden bg-[#D9D9D9]">
                                <img loading="lazy" src="{{ Storage::url($itemNewDompet->thumbnail) }}" class="w-full h-full object-cover" alt="thumbnail">
                            </div>
                            <div class="flex w-full items-center justify-between gap-4">
                                <div class="flex flex-col gap-1">
                                    <h3 class="font-bold text-sm leading-[20px]">{{ $itemNewDompet->name }}</h3>
                                    <p class="text-sm text-[#878785]">{{ $itemNewDompet->category->name }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-1">
                                    <div class="flex">
                                        @for($i = 0; $i < 5; $i++)
                                            <img src="{{asset('assets/images/icons/Star 1.svg')}}" class="w-4 h-4" alt="star">
                                        @endfor
                                    </div>
                                    <p class="font-semibold text-sm">4.5</p>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <p>Belum ada data terbaru</p>
                @endforelse
            </div>
        </section>

        <!-- Bottom Navigation (Mobile Only) -->
        <div id="bottom-nav" class="md:hidden relative flex h-[100px] w-full shrink-0">
            <nav class="fixed bottom-5 w-full px-4 z-30">
                <div class="grid grid-flow-col auto-cols-auto items-center justify-between rounded-full bg-[#2A2A2A] p-2 px-[30px]">
                    <a href="index.html" class="active flex shrink-0 -mx-[22px]">
                        <div class="flex items-center rounded-full gap-2 px-4 py-3 bg-[#C5F277]">
                            <img src="{{asset('assets/images/icons/3dcube.svg')}}" class="w-6 h-6" alt="icon">
                            <span class="font-bold text-sm">Browse</span>
                        </div>
                    </a>
                    <a href="check-booking.html" class="mx-auto">
                        <img src="{{asset('assets/images/icons/bag-2-white.svg')}}" class="w-6 h-6" alt="icon">
                    </a>
                    <a href="#" class="mx-auto">
                        <img src="{{asset('assets/images/icons/star-white.svg')}}" class="w-6 h-6" alt="icon">
                    </a>
                    <a href="#" class="mx-auto">
                        <img src="{{asset('assets/images/icons/24-support-white.svg')}}" class="w-6 h-6" alt="icon">
                    </a>
                </div>
            </nav>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const swiper = new Swiper('.swiper', {
            slidesPerView: 'auto',
            spaceBetween: 12,
            breakpoints: {
                640: {
                    slidesPerView: 2.2,
                },
                768: {
                    slidesPerView: 3.2,
                },
                1024: {
                    slidesPerView: 4.2,
                }
            }
        });
    </script>
</body>
</html>
