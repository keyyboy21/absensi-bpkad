<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
                QR Code Apel Pagi
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                QR Code untuk absensi Apel Pagi pegawai BPKAD.
            </p>
        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-3xl mx-auto px-4">

            {{-- SUCCESS --}}
            @if(session('success'))

                <div
                    class="mb-4 p-4
                           bg-green-100 text-green-700
                           dark:bg-green-900/30 dark:text-green-300
                           rounded-lg"
                >
                    {{ session('success') }}
                </div>

            @endif


            {{-- CARD QR --}}
            <div
                class="bg-white dark:bg-gray-800
                       p-8 rounded-xl shadow text-center"
            >

                <h3
                    class="text-xl font-semibold
                           text-gray-900 dark:text-white"
                >
                    QR Absensi Apel Pagi BPKAD
                </h3>


                <p
                    class="mt-2
                           text-gray-500 dark:text-gray-400"
                >
                    QR Code ini digunakan oleh pegawai untuk membuka
                    halaman absensi Apel Pagi setiap hari Senin.
                </p>


                {{-- INFORMASI --}}
                <div
                    class="mt-6 p-4
                           rounded-lg
                           bg-blue-50 border border-blue-200
                           text-left
                           dark:bg-blue-900/20
                           dark:border-blue-800"
                >

                    <p
                        class="font-semibold
                               text-blue-800 dark:text-blue-300"
                    >
                        Cara Penggunaan
                    </p>


                    <p
                        class="mt-2 text-sm
                               text-blue-700 dark:text-blue-400"
                    >
                        Pegawai login menggunakan akun masing-masing,
                        kemudian scan QR Code ini untuk membuka halaman
                        verifikasi absensi Apel Pagi.
                    </p>


                    <p
                        class="mt-2 text-sm
                               text-blue-700 dark:text-blue-400"
                    >
                        QR Code bersifat permanen, tetapi proses absensi
                        hanya dapat dilakukan pada hari Senin.
                    </p>

                </div>


                @if(!$qrPermanen)

                    {{-- BELUM ADA QR --}}
                    <div class="mt-8">

                        <p
                            class="text-sm
                                   text-gray-500 dark:text-gray-400"
                        >
                            QR Code Apel Pagi belum tersedia.
                        </p>


                        <form
                            action="{{ route('admin.qrcode.generatePermanent') }}"
                            method="POST"
                            class="mt-5"
                        >

                            @csrf


                            <button
                                type="submit"
                                class="px-6 py-3
                                       bg-blue-600
                                       hover:bg-blue-700
                                       text-white
                                       font-semibold
                                       rounded-lg
                                       transition"
                            >
                                Buat QR Apel Pagi
                            </button>

                        </form>

                    </div>


                @else

                    {{-- QR CODE --}}
                    <div class="mt-8">

                        <div
                            class="inline-block
                                   bg-white
                                   p-5
                                   rounded-xl
                                   border border-gray-200"
                        >

                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate(
                                route(
                                    'pegawai.qrcode.verify',
                                    $qrPermanen->token
                                )
                            ) !!}

                        </div>

                    </div>


                    {{-- STATUS --}}
                    <div class="mt-6">

                        <span
                            class="inline-flex
                                   px-4 py-2
                                   rounded-full
                                   text-sm font-semibold
                                   bg-green-100 text-green-700
                                   dark:bg-green-900/40
                                   dark:text-green-300"
                        >
                            QR Code Apel Aktif
                        </span>

                    </div>


                    <p
                        class="mt-4 text-sm
                               text-gray-500 dark:text-gray-400"
                    >
                        QR Code tidak memiliki batas waktu
                        dan dapat digunakan kembali setiap hari Senin.
                    </p>


                    <p
                        class="mt-1 text-sm
                               text-gray-500 dark:text-gray-400"
                    >
                        QR Code dapat dicetak dan ditempatkan
                        di lokasi pelaksanaan Apel Pagi.
                    </p>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>