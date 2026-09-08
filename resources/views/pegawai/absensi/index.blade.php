<x-app-layout>

    <x-slot name="header">
        <div>

            <h2
                class="font-semibold text-xl
                       text-gray-800 dark:text-gray-200
                       leading-tight"
            >
                Status Apel Pagi
            </h2>

            <p
                class="mt-1 text-sm
                       text-gray-500 dark:text-gray-400"
            >
                Informasi status absensi Apel Pagi Anda.
            </p>

        </div>
    </x-slot>


    <div class="py-10">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">


            {{-- ========================= --}}
            {{-- NOTIFIKASI --}}
            {{-- ========================= --}}

            @if(session('success'))

                <div
                    class="mb-4 p-4
                           bg-green-100
                           text-green-700
                           rounded-lg
                           dark:bg-green-900/30
                           dark:text-green-300"
                >
                    {{ session('success') }}
                </div>

            @endif


            @if(session('error'))

                <div
                    class="mb-4 p-4
                           bg-red-100
                           text-red-700
                           rounded-lg
                           dark:bg-red-900/30
                           dark:text-red-300"
                >
                    {{ session('error') }}
                </div>

            @endif



            {{-- ========================= --}}
            {{-- CARD UTAMA --}}
            {{-- ========================= --}}

            <div
                class="bg-white dark:bg-gray-800
                       shadow-sm sm:rounded-xl"
            >

                <div
                    class="p-6
                           text-gray-900
                           dark:text-gray-100"
                >


                    <div
                        class="flex flex-col
                               sm:flex-row
                               sm:items-center
                               sm:justify-between
                               gap-4 mb-6"
                    >

                        <div>

                            <h3
                                class="text-xl font-semibold"
                            >
                                Status Apel Pagi
                            </h3>

                            <p
                                class="mt-1 text-sm
                                       text-gray-500
                                       dark:text-gray-400"
                            >
                                {{
                                    \Carbon\Carbon::now()
                                        ->locale('id')
                                        ->translatedFormat(
                                            'l, d F Y'
                                        )
                                }}
                            </p>

                        </div>


                        <a
                            href="{{ route('pegawai.riwayat') }}"
                            class="inline-flex
                                   items-center
                                   justify-center
                                   px-4 py-2
                                   bg-indigo-600
                                   hover:bg-indigo-700
                                   text-white
                                   text-sm font-semibold
                                   rounded-lg
                                   transition"
                        >
                            Riwayat Apel
                        </a>

                    </div>



                    {{-- ========================= --}}
                    {{-- BUKAN HARI SENIN --}}
                    {{-- ========================= --}}

                    @if(!\Carbon\Carbon::today()->isMonday())

                        <div
                            class="p-6
                                   bg-blue-50
                                   border border-blue-200
                                   rounded-xl
                                   dark:bg-blue-900/20
                                   dark:border-blue-800"
                        >

                            <h4
                                class="font-semibold
                                       text-blue-800
                                       dark:text-blue-300"
                            >
                                Tidak Ada Apel Pagi Hari Ini
                            </h4>


                            <p
                                class="mt-2 text-sm
                                       text-blue-700
                                       dark:text-blue-400"
                            >
                                Absensi Apel Pagi dilaksanakan setiap hari Senin.
                            </p>


                            <p
                                class="mt-2 text-sm
                                       text-gray-600
                                       dark:text-gray-300"
                            >
                                Silakan kembali pada jadwal Apel Pagi berikutnya.
                            </p>

                        </div>



                    {{-- ========================= --}}
                    {{-- SENIN - BELUM ABSEN --}}
                    {{-- ========================= --}}

                    @elseif(!$absensiHariIni)

                        <div
                            class="p-6
                                   bg-yellow-50
                                   border border-yellow-200
                                   rounded-xl
                                   dark:bg-yellow-900/20
                                   dark:border-yellow-800"
                        >

                            <h4
                                class="font-semibold
                                       text-yellow-800
                                       dark:text-yellow-300"
                            >
                                Belum Mengisi Absensi Apel
                            </h4>


                            <p
                                class="mt-2 text-sm
                                       text-yellow-700
                                       dark:text-yellow-400"
                            >
                                Anda belum memiliki status Apel Pagi hari ini.
                            </p>


                            <p
                                class="mt-3 text-sm
                                       text-gray-600
                                       dark:text-gray-300"
                            >
                                Scan QR Code Apel Pagi yang tersedia
                                untuk membuka halaman pengisian absensi.
                            </p>

                        </div>



                    {{-- ========================= --}}
                    {{-- SUDAH ABSEN --}}
                    {{-- ========================= --}}

                    @else

                        <div
                            class="grid grid-cols-1
                                   md:grid-cols-2
                                   gap-5"
                        >


                            {{-- TANGGAL --}}
                            <div
                                class="p-5
                                       rounded-xl
                                       bg-gray-50
                                       dark:bg-gray-700"
                            >

                                <p
                                    class="text-sm
                                           text-gray-500
                                           dark:text-gray-400"
                                >
                                    Tanggal Apel
                                </p>

                                <p
                                    class="mt-2
                                           font-semibold
                                           text-gray-900
                                           dark:text-white"
                                >
                                    {{
                                        \Carbon\Carbon::parse(
                                            $absensiHariIni->tanggal
                                        )
                                        ->locale('id')
                                        ->translatedFormat(
                                            'l, d F Y'
                                        )
                                    }}
                                </p>

                            </div>



                            {{-- JAM --}}
                            <div
                                class="p-5
                                       rounded-xl
                                       bg-gray-50
                                       dark:bg-gray-700"
                            >

                                <p
                                    class="text-sm
                                           text-gray-500
                                           dark:text-gray-400"
                                >
                                    Jam Pengisian
                                </p>

                                <p
                                    class="mt-2
                                           font-semibold
                                           text-gray-900
                                           dark:text-white"
                                >

                                    @if($absensiHariIni->jam_masuk)

                                        {{
                                            \Carbon\Carbon::parse(
                                                $absensiHariIni->jam_masuk
                                            )->format('H:i')
                                        }}

                                    @else

                                        -

                                    @endif

                                </p>

                            </div>

                        </div>



                        {{-- ========================= --}}
                        {{-- STATUS --}}
                        {{-- ========================= --}}

                        <div class="mt-6">

                            <p
                                class="text-sm
                                       text-gray-500
                                       dark:text-gray-400"
                            >
                                Status Kehadiran
                            </p>


                            <div class="mt-3">

                                @if($absensiHariIni->status === 'hadir')

                                    <span
                                        class="inline-flex
                                               px-4 py-2
                                               rounded-full
                                               bg-green-100
                                               text-green-700
                                               font-semibold
                                               dark:bg-green-900/40
                                               dark:text-green-300"
                                    >
                                        Hadir Apel
                                    </span>


                                @elseif($absensiHariIni->status === 'terlambat')

                                    <span
                                        class="inline-flex
                                               px-4 py-2
                                               rounded-full
                                               bg-yellow-100
                                               text-yellow-700
                                               font-semibold
                                               dark:bg-yellow-900/40
                                               dark:text-yellow-300"
                                    >
                                        Terlambat
                                    </span>


                                @elseif($absensiHariIni->status === 'izin')

                                    <span
                                        class="inline-flex
                                               px-4 py-2
                                               rounded-full
                                               bg-blue-100
                                               text-blue-700
                                               font-semibold
                                               dark:bg-blue-900/40
                                               dark:text-blue-300"
                                    >
                                        Izin
                                    </span>


                                @elseif($absensiHariIni->status === 'sakit')

                                    <span
                                        class="inline-flex
                                               px-4 py-2
                                               rounded-full
                                               bg-purple-100
                                               text-purple-700
                                               font-semibold
                                               dark:bg-purple-900/40
                                               dark:text-purple-300"
                                    >
                                        Sakit
                                    </span>


                                @elseif($absensiHariIni->status === 'dinas_luar')

                                    <span
                                        class="inline-flex
                                               px-4 py-2
                                               rounded-full
                                               bg-indigo-100
                                               text-indigo-700
                                               font-semibold
                                               dark:bg-indigo-900/40
                                               dark:text-indigo-300"
                                    >
                                        Dinas Luar
                                    </span>


                                @elseif($absensiHariIni->status === 'lainnya')

                                    <span
                                        class="inline-flex
                                               px-4 py-2
                                               rounded-full
                                               bg-orange-100
                                               text-orange-700
                                               font-semibold
                                               dark:bg-orange-900/40
                                               dark:text-orange-300"
                                    >
                                        Lainnya
                                    </span>


                                @elseif($absensiHariIni->status === 'alpha')

                                    <span
                                        class="inline-flex
                                               px-4 py-2
                                               rounded-full
                                               bg-red-100
                                               text-red-700
                                               font-semibold
                                               dark:bg-red-900/40
                                               dark:text-red-300"
                                    >
                                        Alpha
                                    </span>

                                @endif

                            </div>

                        </div>



                        {{-- ========================= --}}
                        {{-- HADIR / TERLAMBAT --}}
                        {{-- ========================= --}}

                        @if(
                            in_array(
                                $absensiHariIni->status,
                                ['hadir', 'terlambat']
                            )
                        )

                            <div
                                class="mt-6 p-5
                                       rounded-xl
                                       bg-green-50
                                       border border-green-200
                                       dark:bg-green-900/20
                                       dark:border-green-800"
                            >

                                <h4
                                    class="font-semibold
                                           text-green-800
                                           dark:text-green-300"
                                >
                                    Kehadiran Apel Tercatat
                                </h4>


                                <p
                                    class="mt-2 text-sm
                                           text-green-700
                                           dark:text-green-400"
                                >
                                    Absensi Apel Pagi Anda sudah berhasil
                                    disimpan.
                                </p>


                                <div
                                    class="grid grid-cols-1
                                           sm:grid-cols-2
                                           gap-4 mt-5"
                                >


                                    {{-- GPS --}}
                                    <div>

                                        <p
                                            class="text-xs
                                                   text-gray-500
                                                   dark:text-gray-400"
                                        >
                                            Lokasi
                                        </p>


                                        @if(
                                            $absensiHariIni->latitude_masuk &&
                                            $absensiHariIni->longitude_masuk
                                        )

                                            <p
                                                class="mt-1 text-sm
                                                       font-medium
                                                       text-gray-700
                                                       dark:text-gray-200"
                                            >
                                                Lokasi berhasil direkam
                                            </p>

                                        @else

                                            <p
                                                class="mt-1 text-sm
                                                       text-gray-500"
                                            >
                                                Tidak tersedia
                                            </p>

                                        @endif

                                    </div>



                                    {{-- SELFIE --}}
                                    <div>

                                        <p
                                            class="text-xs
                                                   text-gray-500
                                                   dark:text-gray-400"
                                        >
                                            Selfie
                                        </p>


                                        @if($absensiHariIni->foto_masuk)

                                            <p
                                                class="mt-1 text-sm
                                                       font-medium
                                                       text-gray-700
                                                       dark:text-gray-200"
                                            >
                                                Selfie berhasil disimpan
                                            </p>

                                        @else

                                            <p
                                                class="mt-1 text-sm
                                                       text-gray-500"
                                            >
                                                Tidak tersedia
                                            </p>

                                        @endif

                                    </div>

                                </div>

                            </div>



                        {{-- ========================= --}}
                        {{-- TIDAK HADIR --}}
                        {{-- ========================= --}}

                        @elseif(
                            in_array(
                                $absensiHariIni->status,
                                [
                                    'izin',
                                    'sakit',
                                    'dinas_luar',
                                    'lainnya'
                                ]
                            )
                        )

                            <div
                                class="mt-6 p-5
                                       rounded-xl
                                       bg-blue-50
                                       border border-blue-200
                                       dark:bg-blue-900/20
                                       dark:border-blue-800"
                            >

                                <h4
                                    class="font-semibold
                                           text-blue-800
                                           dark:text-blue-300"
                                >
                                    Keterangan Tidak Hadir
                                </h4>


                                <div class="mt-4 space-y-4">


                                    <div>

                                        <p
                                            class="text-xs
                                                   text-gray-500
                                                   dark:text-gray-400"
                                        >
                                            Alasan
                                        </p>

                                        <p
                                            class="mt-1
                                                   font-semibold
                                                   text-gray-900
                                                   dark:text-white"
                                        >
                                            {{
                                                match(
                                                    $absensiHariIni
                                                        ->alasan_tidak_hadir
                                                ) {
                                                    'izin' => 'Izin',
                                                    'sakit' => 'Sakit',
                                                    'dinas_luar' => 'Dinas Luar',
                                                    'lainnya' => 'Lainnya',
                                                    default => '-',
                                                }
                                            }}
                                        </p>

                                    </div>



                                    <div>

                                        <p
                                            class="text-xs
                                                   text-gray-500
                                                   dark:text-gray-400"
                                        >
                                            Keterangan
                                        </p>

                                        <p
                                            class="mt-1
                                                   text-gray-700
                                                   dark:text-gray-200
                                                   whitespace-pre-line"
                                        >
                                            {{
                                                $absensiHariIni->keterangan
                                                ?: '-'
                                            }}
                                        </p>

                                    </div>

                                </div>

                            </div>



                        {{-- ========================= --}}
                        {{-- ALPHA --}}
                        {{-- ========================= --}}

                        @elseif($absensiHariIni->status === 'alpha')

                            <div
                                class="mt-6 p-5
                                       rounded-xl
                                       bg-red-50
                                       border border-red-200
                                       dark:bg-red-900/20
                                       dark:border-red-800"
                            >

                                <h4
                                    class="font-semibold
                                           text-red-800
                                           dark:text-red-300"
                                >
                                    Tidak Mengikuti Apel
                                </h4>


                                <p
                                    class="mt-2 text-sm
                                           text-red-700
                                           dark:text-red-400"
                                >
                                    Anda tercatat tidak mengikuti Apel Pagi
                                    tanpa keterangan.
                                </p>

                            </div>

                        @endif



                        {{-- ========================= --}}
                        {{-- ABSENSI SUDAH TERKIRIM --}}
                        {{-- ========================= --}}

                        <div
                            class="mt-6 p-4
                                   rounded-lg
                                   bg-gray-100
                                   text-gray-700
                                   dark:bg-gray-700
                                   dark:text-gray-300"
                        >
                            Absensi Apel Pagi untuk hari ini sudah tercatat.
                            Anda tidak perlu melakukan pengisian ulang.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>