<footer class="bg-[#0f172a] text-white pt-16 pb-8 border-t border-gray-800">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                <div>
                    <div class="flex items-center gap-3 mb-6">
                    <img src="/koni-app/assets/img/logo.png" alt="Logo KONI" alt="Logo" class="h-12 bg-white rounded-full p-1">
                        <div>
                            <h2 class="text-xl font-bold font-heading leading-none">KONI</h2>
                            <p class="text-xs text-gray-400 font-bold tracking-widest uppercase">Kab. Banyumas</p>
                        </div>
                    </div>
                    <div class="text-gray-400 text-sm space-y-3">
                        <p class="leading-relaxed">
                            Komplek GOR Satria, Jl. Prof. Dr. Suharso No. 1, <br>
                            Purwokerto, Jawa Tengah 53114
                        </p>
                        <div class="flex items-center gap-2"><i class="fa-solid fa-phone text-red-500"></i> (0281) 636xxx</div>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-bold font-heading mb-6 border-l-4 border-red-600 pl-3">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Profil Organisasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Cabang Olahraga</a></li>
                        <li><a href="#" class="hover:text-white transition">Data Atlet</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold font-heading mb-6 border-l-4 border-red-600 pl-3">Lokasi</h4>
                    <div class="w-full h-40 bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-700">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3956.273752677764!2d109.2468813147761!3d-7.412165994651375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e655e8f4c28723d%3A0x62662c9545468726!2sGOR%20Satria%20Purwokerto!5e0!3m2!1sen!2sid!4v1677834567890!5m2!1sen!2sid" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-8 text-center text-xs text-gray-500">
                © 2026 Tim PKM TI UMP. All Rights Reserved.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            spaceBetween: 0, effect: "fade", centeredSlides: true,
            autoplay: { delay: 5000, disableOnInteraction: false },
            pagination: { el: ".swiper-pagination", clickable: true },
            navigation: { nextEl: ".swiper-button-next", prevEl: ".swiper-button-prev" },
        });

        window.addEventListener('load', function() {
            setTimeout(() => {
                const preloader = document.getElementById('preloader');
                if(preloader) {
                    preloader.style.opacity = '0';
                    setTimeout(() => {
                        preloader.style.display = 'none';
                        document.body.style.overflow = 'auto'; 
                    }, 500);
                }
            }, 1000);
        });
        
        const counters = document.querySelectorAll('.counter');
        const speed = 200; 
        const animateCounters = () => {
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / speed;
                    if (count < target) { counter.innerText = Math.ceil(count + inc); setTimeout(updateCount, 20); } else { counter.innerText = target; }
                }; updateCount();
            });
        };
        let hasAnimated = false;
        window.addEventListener('scroll', () => {
            const section = document.getElementById('medali');
            if(section) {
                const position = section.getBoundingClientRect().top;
                const screenPosition = window.innerHeight / 1.3;
                if (position < screenPosition && !hasAnimated) { animateCounters(); hasAnimated = true; }
            }
        });

        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const profilBtn = document.getElementById('mobile-profil-btn');
        const profilMenu = document.getElementById('mobile-profil-menu');

        if(btn){
            btn.addEventListener('click', () => { menu.classList.toggle('hidden'); });
        }
        if(profilBtn){
            profilBtn.addEventListener('click', () => {
                profilMenu.classList.toggle('hidden');
                const icon = profilBtn.querySelector('i');
                if(profilMenu.classList.contains('hidden')){
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        }
    </script>
</body>
</html>