<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings['school_name'] ?? 'SISARPA' }} - Sistem Informasi Sarana & Prasarana</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        html { scroll-behavior: smooth; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        section { scroll-margin-top: 5rem; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <!-- Header / Navbar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    @if(isset($settings['school_logo']))
                        <img src="{{ asset('storage/settings/' . $settings['school_logo']) }}" alt="Logo" class="h-10 w-auto">
                    @else
                        <div class="bg-blue-600 p-2 rounded-lg text-white">
                            <i class="fas fa-school text-xl"></i>
                        </div>
                    @endif
                    <span class="text-xl font-bold tracking-tight text-slate-800">{{ $settings['school_name'] ?? 'SISARPA' }}</span>
                </div>
                <div class="hidden md:flex gap-8 text-sm font-semibold text-slate-600">
                    <a href="#about" class="hover:text-blue-600 transition">Beranda</a>
                    <a href="#katalog" class="hover:text-blue-600 transition">Katalog Aset</a>
                    <a href="#ruangan" class="hover:text-blue-600 transition">Cek Ruangan</a>
                    <a href="#prosedur" class="hover:text-blue-600 transition">Panduan Layanan</a>
                </div>
                <div>
                    <a href="{{ route('login') }}" class="bg-blue-600 text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Area Petugas
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="bg-white border-b border-slate-100 py-16 md:py-24 overflow-hidden relative" id="about">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-blue-600 font-bold tracking-widest text-sm uppercase mb-4">{{ $settings['school_city'] ?? 'Official Platform' }}</h2>
                    <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight mb-6">
                        Sistem Monitoring Fasilitas {{ $settings['school_name'] ?? 'Sekolah' }}
                    </h1>
                    <p class="text-lg text-slate-600 mb-8 leading-relaxed">
                        Selamat datang di portal informasi Sarana & Prasarana. Kami memastikan seluruh fasilitas pendidikan dalam kondisi prima untuk menunjang kegiatan belajar mengajar.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <div class="bg-slate-100 p-4 rounded-xl border border-slate-200 w-40 text-center">
                            <i class="fas fa-box text-blue-500 mb-2 block"></i>
                            <span class="text-2xl font-bold block">{{ $stats['total_barang'] ?? 0 }}</span>
                            <span class="text-xs text-slate-500 uppercase font-semibold">Unit Aset</span>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-xl border border-amber-100 w-40 text-center">
                            <i class="fas fa-tags text-amber-500 mb-2 block"></i>
                            <span class="text-2xl font-bold block">{{ $stats['kategori_count'] ?? 0 }}</span>
                            <span class="text-xs text-slate-500 uppercase font-semibold">Kategori</span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="relative">
                        <div class="absolute -top-10 -right-10 bg-blue-100 w-72 h-72 rounded-full blur-3xl opacity-50"></div>
                        <div class="bg-white p-6 rounded-3xl shadow-2xl border border-slate-100 relative">
                             <img src="https://images.unsplash.com/photo-1541339907198-e08756ebafe3?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="School Lab" class="rounded-2xl shadow-inner">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Quick Actions for Roles -->
    <section class="py-12 bg-white -mt-10 relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-6">
            <!-- For Students -->
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 group hover:bg-blue-600 hover:text-white transition-all">
                <div class="bg-blue-50 w-14 h-14 rounded-2xl flex items-center justify-center text-blue-600 mb-6 group-hover:bg-white/20 group-hover:text-white transition">
                    <i class="fas fa-user-graduate text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Area Siswa</h3>
                <p class="text-slate-500 group-hover:text-blue-100 text-sm leading-relaxed mb-6">Gunakan NISN untuk login. Pinjam alat laboratorium, olahraga, atau lapor kerusakan fasilitas kelas.</p>
                <a href="{{ route('login', ['as' => 'siswa']) }}" class="font-bold text-blue-600 group-hover:text-white flex items-center gap-2">Masuk Siswa <i class="fas fa-arrow-right text-xs"></i></a>
            </div>
            <!-- For Teachers -->
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 group hover:bg-emerald-600 hover:text-white transition-all">
                <div class="bg-emerald-50 w-14 h-14 rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:bg-white/20 group-hover:text-white transition">
                    <i class="fas fa-chalkboard-teacher text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Area Guru</h3>
                <p class="text-slate-500 group-hover:text-emerald-100 text-sm leading-relaxed mb-6">Booking ruangan lab, ruang rapat, atau pinjam proyektor menggunakan akun NUPTK/Email.</p>
                <a href="{{ route('login', ['as' => 'guru']) }}" class="font-bold text-emerald-600 group-hover:text-white flex items-center gap-2">Masuk Guru <i class="fas fa-arrow-right text-xs"></i></a>
            </div>
            <!-- For Visitors/Public -->
            <div class="bg-white p-8 rounded-3xl shadow-xl border border-slate-100 group hover:bg-amber-500 hover:text-white transition-all">
                <div class="bg-amber-50 w-14 h-14 rounded-2xl flex items-center justify-center text-amber-600 mb-6 group-hover:bg-white/20 group-hover:text-white transition">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-3">Lapor Publik</h3>
                <p class="text-slate-500 group-hover:text-amber-100 text-sm leading-relaxed mb-6">Masyarakat sekolah dapat melaporkan temuan kerusakan fasilitas tanpa harus login ke sistem.</p>
                <a href="{{ route('laporan-kerusakan.create') }}" class="font-bold text-amber-600 group-hover:text-white flex items-center gap-2">Lapor Sekarang <i class="fas fa-arrow-right text-xs"></i></a>
            </div>
        </div>
    </section>

    <!-- Featured Asset Section -->
    <section class="py-20 bg-slate-50" id="katalog">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                <div class="max-w-xl text-left">
                    <h2 class="text-3xl font-bold text-slate-900 mb-4 font-extrabold uppercase tracking-tight">Katalog Inventaris Terbaru</h2>
                    <p class="text-slate-600">Beberapa aset sekolah dalam kondisi <span class="text-emerald-600 font-bold">BAIK</span> yang siap dipergunakan untuk menunjang praktik dan pembelajaran.</p>
                </div>
                <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline">Lihat Semua Katalog <i class="fas fa-external-link-alt ml-1 small"></i></a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @forelse($featured_assets as $asset)
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-2xl transition-all group">
                    <div class="h-48 overflow-hidden relative">
                         @if($asset->foto_barang)
                            <img src="{{ asset('storage/' . $asset->foto_barang) }}" alt="{{ $asset->nama_barang }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                         @else
                            <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="fas fa-image fa-3x"></i>
                            </div>
                         @endif
                         <div class="absolute top-3 left-3">
                            <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-extrabold text-slate-800 shadow-sm border border-slate-100">
                                {{ $asset->kategori->nama_kategori ?? 'Aset' }}
                            </span>
                         </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-slate-800 mb-2 truncate">{{ $asset->nama_barang }}</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-semibold text-slate-500"><i class="fas fa-warehouse mr-1"></i> {{ $asset->lokasi }}</span>
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Stok: {{ $asset->stok }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <p class="col-span-full text-center text-slate-400 italic py-10">Belum ada katalog barang yang dipasang.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Room Status Section -->
    <section class="py-20 bg-white" id="ruangan">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900 mb-4 font-extrabold uppercase">Cek Ketersediaan Ruangan</h2>
            <p class="text-slate-600">Jadwal penggunaan gedung & laboratorium hari ini: <span class="font-extrabold text-blue-600">{{ now()->translatedFormat('d F Y') }}</span></p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-x-auto pb-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($ruangans as $ruangan)
                @php $is_booked = in_array($ruangan->id, $reservations ?? []); @endphp
                <div class="bg-slate-50 p-6 rounded-3xl shadow-sm border {{ $is_booked ? 'border-red-100' : 'border-slate-100' }} hover:shadow-xl transition-all">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 {{ $is_booked ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }} rounded-2xl">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <span class="px-2 py-1 text-[10px] font-extrabold uppercase rounded-lg {{ $is_booked ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $is_booked ? 'Sedang Dipakai' : 'Bisa Dipakai' }}
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg mb-1 leading-tight">{{ $ruangan->nama_ruangan }}</h3>
                    <p class="text-xs text-slate-500 flex items-center gap-1">
                        <i class="fas fa-map-marker-alt"></i> Gedung {{ $ruangan->lantai ?? 'Lt. 1' }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Procedure Section -->
    <section class="py-24 bg-slate-50" id="prosedur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 rounded-[3rem] p-8 md:p-16 text-white relative overflow-hidden shadow-2xl">
                <div class="grid md:grid-cols-2 gap-16 relative z-10">
                    <div>
                        <h2 class="text-3xl md:text-5xl font-extrabold mb-8 leading-tight">Prosedur Layanan Sarpras SMK</h2>
                        <p class="text-slate-400 leading-relaxed mb-10 text-lg">
                            Kami memudahkan akses fasilitas agar kegiatan pembelajaran tidak terganggu. Ikuti panduan sederhana di samping untuk mulai menggunakan layanan.
                        </p>
                        <div class="flex flex-col gap-4">
                            <div class="bg-white/10 p-5 rounded-3xl border border-white/20 flex items-center gap-4">
                                <div class="bg-blue-600 h-10 w-10 rounded-full flex items-center justify-center font-bold">!</div>
                                <span class="text-sm font-semibold">Gunakan Akun Sekolah yang sudah terdaftar di Buku Induk Digital.</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <ul class="space-y-10">
                            <li class="flex gap-6">
                                <div class="bg-blue-600 h-12 w-12 rounded-2xl flex items-center justify-center font-extrabold text-xl flex-shrink-0">01</div>
                                <div>
                                    <h4 class="font-bold text-2xl mb-2 text-white italic">PILIH & PESAN</h4>
                                    <p class="text-slate-400 text-sm leading-relaxed">Login ke dashboard, pilih barang atau ruangan yang ingin dipergunakan, tentukan jadwalnya.</p>
                                </div>
                            </li>
                            <li class="flex gap-6">
                                <div class="bg-emerald-600 h-12 w-12 rounded-2xl flex items-center justify-center font-extrabold text-xl flex-shrink-0">02</div>
                                <div>
                                    <h4 class="font-bold text-2xl mb-2 text-white italic">TANDA TANGAN</h4>
                                    <p class="text-slate-400 text-sm leading-relaxed">Berikan tanda tangan digital di layar sebagai bukti serah terima barang yang sah.</p>
                                </div>
                            </li>
                            <li class="flex gap-6">
                                <div class="bg-amber-500 h-12 w-12 rounded-2xl flex items-center justify-center font-extrabold text-xl flex-shrink-0">03</div>
                                <div>
                                    <h4 class="font-bold text-2xl mb-2 text-white italic">LAPOR KONDISI</h4>
                                    <p class="text-slate-400 text-sm leading-relaxed">Kembalikan tepat waktu dan laporkan jika ada kerusakan agar segera diperbaiki oleh tim teknis.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-2xl font-bold mb-4 flex items-center justify-center gap-2">
                @if(isset($settings['school_logo']))
                    <img src="{{ asset('storage/settings/' . $settings['school_logo']) }}" alt="Logo" class="h-8 w-auto">
                @else
                    <i class="fas fa-school text-blue-500"></i>
                @endif
                {{ $settings['school_name'] ?? 'SISARPA' }}
            </h3>
            <p class="text-slate-400 mb-8">{{ $settings['school_address'] ?? 'Official Asset Management System' }} | {{ $settings['school_city'] ?? '' }}</p>
            <div class="flex justify-center gap-6 mb-12">
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-xl"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-xl"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-slate-400 hover:text-blue-600 transition text-xl"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="pt-8 text-[10px] text-slate-400 font-extrabold tracking-widest uppercase">
                &copy; {{ date('Y') }} SISTEM INVENTARIS SARANA PRASARANA SEKOLAH. ALL RIGHTS RESERVED.
            </div>
        </div>
    </footer>

    <script>
        // Active Menu on Scroll Logic
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.6 // Trigger when 60% of section is visible
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const id = entry.target.getAttribute('id');
                const menuLink = document.querySelector(`nav a[href="#${id}"]`);
                
                if (entry.isIntersecting) {
                    // Remove active from all links
                    document.querySelectorAll('nav a').forEach(link => {
                        link.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                        link.classList.add('text-slate-600');
                    });
                    
                    // Add active to current link
                    if (menuLink) {
                        menuLink.classList.remove('text-slate-600');
                        menuLink.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    }
                }
            });
        }, observerOptions);

        // Selection of sections to monitor
        document.querySelectorAll('header[id], section[id]').forEach((section) => {
            observer.observe(section);
        });
    </script>
</body>
</html>
