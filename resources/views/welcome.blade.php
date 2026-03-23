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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
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
                    <a href="#about" class="hover:text-blue-600 transition">Tentang</a>
                    <a href="#ruangan" class="hover:text-blue-600 transition">Cek Ruangan</a>
                    <a href="#prosedur" class="hover:text-blue-600 transition">Prosedur Lapor</a>
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
                        <div class="bg-green-50 p-4 rounded-xl border border-green-100 w-40 text-center">
                            <i class="fas fa-check-circle text-green-500 mb-2 block"></i>
                            <span class="text-2xl font-bold block">{{ $stats['barang_tersedia'] ?? 0 }}</span>
                            <span class="text-xs text-slate-500 uppercase font-semibold">Kondisi Baik</span>
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

    <!-- Room Status Section -->
    <section class="py-20 bg-slate-50" id="ruangan">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-12">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Status Penggunaan Ruangan</h2>
            <p class="text-slate-600">Data ketersediaan ruangan per hari ini: <span class="font-bold text-slate-900 underline">{{ now()->translatedFormat('d F Y') }}</span></p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 overflow-x-auto pb-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($ruangans as $ruangan)
                @php $is_booked = in_array($ruangan->id, $reservations ?? []); @endphp
                <div class="bg-white p-6 rounded-2xl shadow-sm border {{ $is_booked ? 'border-red-100' : 'border-slate-200' }} hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 {{ $is_booked ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600' }} rounded-xl">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <span class="px-2 py-1 text-[10px] font-extrabold uppercase rounded-lg {{ $is_booked ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                            {{ $is_booked ? 'Dipakai' : 'Tersedia' }}
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
    <section class="py-24 bg-white" id="prosedur">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-blue-600 rounded-[2.5rem] p-8 md:p-16 text-white relative overflow-hidden">
                <div class="grid md:grid-cols-2 gap-12 relative z-10">
                    <div>
                        <h2 class="text-3xl md:text-4xl font-extrabold mb-8">Alur Pelaporan Kerusakan Barang</h2>
                        <p class="text-blue-100 leading-relaxed mb-6">
                            Jika Anda menemukan fasilitas atau inventaris sekolah yang rusak, mohon segara melaporkan agar dapat segera diperbaiki oleh tim Sarana Prasarana.
                        </p>
                        <div class="flex gap-4">
                            <a href="{{ route('laporan-kerusakan.create') }}" class="bg-white text-blue-600 px-6 py-3 rounded-xl font-bold text-sm hover:bg-blue-50 transition">
                                <i class="fas fa-paper-plane mr-2"></i> Kirim Laporan Sekarang
                            </a>
                        </div>
                    </div>
                    <div>
                        <ul class="space-y-6">
                            <li class="flex gap-4">
                                <div class="bg-white/20 h-8 w-8 rounded-full flex items-center justify-center font-bold flex-shrink-0">1</div>
                                <div>
                                    <h4 class="font-bold text-xl mb-1 text-white">Lapor via Portal</h4>
                                    <p class="text-blue-100 text-sm">Buka menu 'Area Petugas' atau klik tombol lapor di samping.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="bg-white/20 h-8 w-8 rounded-full flex items-center justify-center font-bold flex-shrink-0">2</div>
                                <div>
                                    <h4 class="font-bold text-xl mb-1 text-white">Verifikasi Kondisi</h4>
                                    <p class="text-blue-100 text-sm">Petugas Sarpras akan menuju lokasi untuk pengecekan fisik barang.</p>
                                </div>
                            </li>
                            <li class="flex gap-4">
                                <div class="bg-white/20 h-8 w-8 rounded-full flex items-center justify-center font-bold flex-shrink-0">3</div>
                                <div>
                                    <h4 class="font-bold text-xl mb-1 text-white">Tindakan Perbaikan</h4>
                                    <p class="text-blue-100 text-sm">Service atau pemeliharaan dijadwalkan agar barang berfungsi kembali.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Subtle background elements -->
                <div class="absolute -bottom-20 -right-20 bg-white/10 w-64 h-64 rounded-full blur-3xl"></div>
                <div class="absolute -top-20 -left-20 bg-blue-400 w-64 h-64 rounded-full blur-3xl opacity-30"></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h3 class="text-2xl font-bold mb-4 flex items-center justify-center gap-2">
                @if(isset($settings['school_logo']))
                    <img src="{{ asset('storage/settings/' . $settings['school_logo']) }}" alt="Logo" class="h-8 w-auto">
                @else
                    <i class="fas fa-school text-blue-500"></i>
                @endif
                {{ $settings['school_name'] ?? 'SISARPA' }}
            </h3>
            <p class="text-slate-400 mb-8">{{ $settings['school_address'] ?? 'Official Asset Management System' }}</p>
            <div class="flex justify-center gap-6 mb-12">
                <a href="#" class="text-white hover:text-blue-400 transition text-xl"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-white hover:text-blue-400 transition text-xl"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-white hover:text-blue-400 transition text-xl"><i class="fab fa-youtube"></i></a>
            </div>
            <div class="border-t border-slate-800 pt-8 text-xs text-slate-500 font-semibold tracking-widest uppercase">
                &copy; {{ date('Y') }} SISTEM INVENTARIS SARANA PRASARANA SEKOLAH. ALL RIGHTS RESERVED.
            </div>
        </div>
    </footer>

</body>
</html>
