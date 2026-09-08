<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Absensi Apel Pagi
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Silakan pilih status kehadiran Apel Pagi.
            </p>
        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden">

                <div class="p-6">


                    {{-- ALERT ERROR --}}
                    @if(session('error'))

                        <div
                            class="mb-6 rounded-lg
                                   bg-red-50 border border-red-200
                                   px-4 py-3
                                   text-sm text-red-700
                                   dark:bg-red-900/20
                                   dark:border-red-800
                                   dark:text-red-300"
                        >
                            {{ session('error') }}
                        </div>

                    @endif


                    {{-- VALIDATION ERROR --}}
                    @if($errors->any())

                        <div
                            class="mb-6 rounded-lg
                                   bg-red-50 border border-red-200
                                   px-4 py-3
                                   text-sm text-red-700
                                   dark:bg-red-900/20
                                   dark:border-red-800
                                   dark:text-red-300"
                        >

                            <ul class="list-disc list-inside space-y-1">

                                @foreach($errors->all() as $error)

                                    <li>
                                        {{ $error }}
                                    </li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    {{-- INFORMASI --}}
                    <div
                        class="mb-6 rounded-xl
                               bg-blue-50 border border-blue-200
                               p-4
                               dark:bg-blue-900/20
                               dark:border-blue-800"
                    >

                        <p class="font-semibold text-blue-800 dark:text-blue-300">
                            Verifikasi Absensi Apel Pagi
                        </p>

                        <p class="mt-1 text-sm text-blue-700 dark:text-blue-400">
                            QR Code telah berhasil diverifikasi.
                            Pilih apakah Anda hadir atau tidak hadir pada Apel Pagi.
                        </p>

                    </div>


                    <form
                        id="formAbsensi"
                        method="POST"
                        action="{{ route('pegawai.absensi.masuk') }}"
                    >

                        @csrf


                        {{-- =============================== --}}
                        {{-- PILIH KEHADIRAN --}}
                        {{-- =============================== --}}

                        <div class="mb-8">

                            <label
                                class="block mb-3
                                       text-sm font-semibold
                                       text-gray-700 dark:text-gray-300"
                            >
                                Status Kehadiran
                            </label>


                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                                {{-- HADIR --}}
                                <label
                                    id="cardHadir"
                                    class="relative flex cursor-pointer
                                           rounded-xl border-2
                                           border-gray-200 dark:border-gray-700
                                           p-5 transition
                                           hover:border-green-500"
                                >

                                    <input
                                        type="radio"
                                        name="kehadiran"
                                        value="hadir"
                                        class="mt-1"
                                        {{ old('kehadiran') === 'hadir' ? 'checked' : '' }}
                                    >

                                    <div class="ml-3">

                                        <p
                                            class="font-semibold
                                                   text-gray-900 dark:text-white"
                                        >
                                            Hadir Apel
                                        </p>

                                        <p
                                            class="mt-1 text-sm
                                                   text-gray-500 dark:text-gray-400"
                                        >
                                            Wajib melakukan selfie
                                            dan verifikasi lokasi kantor.
                                        </p>

                                    </div>

                                </label>


                                {{-- TIDAK HADIR --}}
                                <label
                                    id="cardTidakHadir"
                                    class="relative flex cursor-pointer
                                           rounded-xl border-2
                                           border-gray-200 dark:border-gray-700
                                           p-5 transition
                                           hover:border-red-500"
                                >

                                    <input
                                        type="radio"
                                        name="kehadiran"
                                        value="tidak_hadir"
                                        class="mt-1"
                                        {{ old('kehadiran') === 'tidak_hadir' ? 'checked' : '' }}
                                    >

                                    <div class="ml-3">

                                        <p
                                            class="font-semibold
                                                   text-gray-900 dark:text-white"
                                        >
                                            Tidak Hadir Apel
                                        </p>

                                        <p
                                            class="mt-1 text-sm
                                                   text-gray-500 dark:text-gray-400"
                                        >
                                            Pilih alasan ketidakhadiran
                                            dan isi keterangan.
                                        </p>

                                    </div>

                                </label>

                            </div>

                        </div>


                        {{-- =============================== --}}
                        {{-- BAGIAN HADIR --}}
                        {{-- =============================== --}}

                        <div
                            id="bagianHadir"
                            style="display: none;"
                        >

                            <div
                                class="border-t border-gray-200
                                       dark:border-gray-700
                                       pt-6"
                            >

                                <h3
                                    class="text-lg font-semibold
                                           text-gray-900 dark:text-white"
                                >
                                    Verifikasi Kehadiran
                                </h3>

                                <p
                                    class="mt-1 mb-5 text-sm
                                           text-gray-500 dark:text-gray-400"
                                >
                                    Pastikan Anda berada di area Kantor BPKAD
                                    kemudian lakukan selfie.
                                </p>


                                {{-- STATUS LOKASI --}}
                                <div
                                    id="statusLokasi"
                                    class="mb-5 rounded-lg
                                           bg-gray-50
                                           border border-gray-200
                                           p-4
                                           text-sm
                                           text-gray-600
                                           dark:bg-gray-900
                                           dark:border-gray-700
                                           dark:text-gray-300"
                                >
                                    Lokasi belum diperiksa.
                                </div>


                                {{-- INPUT GPS --}}
                                <input
                                    type="hidden"
                                    name="latitude"
                                    id="latitude"
                                    value="{{ old('latitude') }}"
                                >

                                <input
                                    type="hidden"
                                    name="longitude"
                                    id="longitude"
                                    value="{{ old('longitude') }}"
                                >


                                {{-- CAMERA --}}
                                <div
                                    id="cameraContainer"
                                    class="overflow-hidden
                                           rounded-xl
                                           bg-black"
                                >

                                    <video
                                        id="video"
                                        autoplay
                                        playsinline
                                        class="w-full"
                                        style="max-height: 430px;"
                                    ></video>

                                </div>


                                {{-- PREVIEW --}}
                                <div
                                    id="previewContainer"
                                    class="overflow-hidden
                                           rounded-xl
                                           bg-black"
                                    style="display: none;"
                                >

                                    <img
                                        id="preview"
                                        alt="Preview Selfie"
                                        class="w-full object-cover"
                                        style="max-height: 430px;"
                                    >

                                </div>


                                <canvas
                                    id="canvas"
                                    style="display: none;"
                                ></canvas>


                                {{-- FOTO BASE64 --}}
                                <input
                                    type="hidden"
                                    name="foto"
                                    id="foto"
                                >


                                {{-- TOMBOL FOTO --}}
                                <div class="mt-5 flex flex-wrap gap-3">

                                    <button
                                        type="button"
                                        id="btnAmbilFoto"
                                        class="inline-flex items-center
                                               px-5 py-3
                                               rounded-lg
                                               bg-indigo-600
                                               hover:bg-indigo-700
                                               text-white
                                               text-sm font-semibold
                                               transition"
                                    >
                                        Ambil Selfie
                                    </button>


                                    <button
                                        type="button"
                                        id="btnUlangiFoto"
                                        style="display: none;"
                                        class="inline-flex items-center
                                               px-5 py-3
                                               rounded-lg
                                               bg-gray-600
                                               hover:bg-gray-700
                                               text-white
                                               text-sm font-semibold
                                               transition"
                                    >
                                        Ulangi Foto
                                    </button>

                                </div>

                            </div>

                        </div>


                        {{-- =============================== --}}
                        {{-- BAGIAN TIDAK HADIR --}}
                        {{-- =============================== --}}

                        <div
                            id="bagianTidakHadir"
                            style="display: none;"
                        >

                            <div
                                class="border-t border-gray-200
                                       dark:border-gray-700
                                       pt-6"
                            >

                                <h3
                                    class="text-lg font-semibold
                                           text-gray-900 dark:text-white"
                                >
                                    Alasan Tidak Hadir
                                </h3>

                                <p
                                    class="mt-1 mb-5 text-sm
                                           text-gray-500 dark:text-gray-400"
                                >
                                    Pilih alasan dan berikan keterangan
                                    mengapa Anda tidak mengikuti Apel Pagi.
                                </p>


                                {{-- ALASAN --}}
                                <div class="mb-5">

                                    <label
                                        for="alasan_tidak_hadir"
                                        class="block mb-2
                                               text-sm font-medium
                                               text-gray-700 dark:text-gray-300"
                                    >
                                        Alasan
                                    </label>

                                    <select
                                        name="alasan_tidak_hadir"
                                        id="alasan_tidak_hadir"
                                        class="block w-full
                                               rounded-lg
                                               border-gray-300
                                               dark:border-gray-700
                                               dark:bg-gray-900
                                               dark:text-white
                                               focus:border-indigo-500
                                               focus:ring-indigo-500"
                                    >

                                        <option value="">
                                            -- Pilih Alasan --
                                        </option>

                                        <option
                                            value="izin"
                                            {{ old('alasan_tidak_hadir') === 'izin' ? 'selected' : '' }}
                                        >
                                            Izin
                                        </option>

                                        <option
                                            value="sakit"
                                            {{ old('alasan_tidak_hadir') === 'sakit' ? 'selected' : '' }}
                                        >
                                            Sakit
                                        </option>

                                        <option
                                            value="dinas_luar"
                                            {{ old('alasan_tidak_hadir') === 'dinas_luar' ? 'selected' : '' }}
                                        >
                                            Dinas Luar
                                        </option>

                                        <option
                                            value="lainnya"
                                            {{ old('alasan_tidak_hadir') === 'lainnya' ? 'selected' : '' }}
                                        >
                                            Lainnya
                                        </option>

                                    </select>

                                </div>


                                {{-- KETERANGAN --}}
                                <div>

                                    <label
                                        for="keterangan"
                                        class="block mb-2
                                               text-sm font-medium
                                               text-gray-700 dark:text-gray-300"
                                    >
                                        Keterangan
                                    </label>

                                    <textarea
                                        name="keterangan"
                                        id="keterangan"
                                        rows="5"
                                        maxlength="1000"
                                        placeholder="Tuliskan alasan tidak mengikuti Apel Pagi..."
                                        class="block w-full
                                               rounded-lg
                                               border-gray-300
                                               dark:border-gray-700
                                               dark:bg-gray-900
                                               dark:text-white
                                               focus:border-indigo-500
                                               focus:ring-indigo-500"
                                    >{{ old('keterangan') }}</textarea>

                                </div>

                            </div>

                        </div>


                        {{-- =============================== --}}
                        {{-- SUBMIT --}}
                        {{-- =============================== --}}

                        <div
                            id="bagianSubmit"
                            class="mt-8"
                            style="display: none;"
                        >

                            <button
                                type="submit"
                                id="btnKirim"
                                class="w-full inline-flex
                                       justify-center items-center
                                       px-6 py-3
                                       rounded-lg
                                       bg-green-600
                                       hover:bg-green-700
                                       text-white
                                       font-semibold
                                       transition"
                            >
                                Kirim Absensi Apel Pagi
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>


    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const form = document.getElementById('formAbsensi');

            const pilihanKehadiran =
                document.querySelectorAll(
                    'input[name="kehadiran"]'
                );

            const bagianHadir =
                document.getElementById('bagianHadir');

            const bagianTidakHadir =
                document.getElementById('bagianTidakHadir');

            const bagianSubmit =
                document.getElementById('bagianSubmit');

            const video =
                document.getElementById('video');

            const canvas =
                document.getElementById('canvas');

            const preview =
                document.getElementById('preview');

            const previewContainer =
                document.getElementById('previewContainer');

            const cameraContainer =
                document.getElementById('cameraContainer');

            const btnAmbilFoto =
                document.getElementById('btnAmbilFoto');

            const btnUlangiFoto =
                document.getElementById('btnUlangiFoto');

            const fotoInput =
                document.getElementById('foto');

            const latitudeInput =
                document.getElementById('latitude');

            const longitudeInput =
                document.getElementById('longitude');

            const statusLokasi =
                document.getElementById('statusLokasi');

            const alasanTidakHadir =
                document.getElementById('alasan_tidak_hadir');

            const keterangan =
                document.getElementById('keterangan');

            let stream = null;

            let lokasiSiap = false;

            let fotoSiap = false;


            /*
            |--------------------------------------------------------------------------
            | Cek Pilihan Kehadiran
            |--------------------------------------------------------------------------
            */

            function updatePilihan()
            {
                const pilihan =
                    document.querySelector(
                        'input[name="kehadiran"]:checked'
                    );


                if (!pilihan) {

                    bagianHadir.style.display = 'none';

                    bagianTidakHadir.style.display = 'none';

                    bagianSubmit.style.display = 'none';

                    stopCamera();

                    return;
                }


                bagianSubmit.style.display = 'block';


                if (pilihan.value === 'hadir') {

                    bagianHadir.style.display = 'block';

                    bagianTidakHadir.style.display = 'none';


                    alasanTidakHadir.value = '';

                    keterangan.value = '';


                    cekLokasi();

                    bukaCamera();

                } else {

                    bagianHadir.style.display = 'none';

                    bagianTidakHadir.style.display = 'block';


                    stopCamera();

                    resetFoto();


                    latitudeInput.value = '';

                    longitudeInput.value = '';

                    lokasiSiap = false;
                }
            }


            pilihanKehadiran.forEach(function (radio) {

                radio.addEventListener(
                    'change',
                    updatePilihan
                );

            });


            /*
            |--------------------------------------------------------------------------
            | GPS
            |--------------------------------------------------------------------------
            */

            function cekLokasi()
            {
                lokasiSiap = false;

                statusLokasi.innerHTML =
                    'Sedang mengambil lokasi Anda...';


                if (!navigator.geolocation) {

                    statusLokasi.innerHTML =
                        'Browser Anda tidak mendukung GPS.';

                    return;
                }


                navigator.geolocation.getCurrentPosition(

                    function (position) {

                        latitudeInput.value =
                            position.coords.latitude;

                        longitudeInput.value =
                            position.coords.longitude;

                        lokasiSiap = true;


                        statusLokasi.innerHTML =
                            '✓ Lokasi berhasil diperoleh. ' +
                            'Jarak dengan kantor akan diverifikasi saat absensi dikirim.';

                    },

                    function (error) {

                        lokasiSiap = false;

                        latitudeInput.value = '';

                        longitudeInput.value = '';


                        let pesan =
                            'Lokasi tidak dapat diperoleh.';


                        if (error.code === 1) {

                            pesan =
                                'Izin lokasi ditolak. ' +
                                'Silakan izinkan akses lokasi pada browser.';

                        } else if (error.code === 2) {

                            pesan =
                                'Lokasi perangkat tidak tersedia.';

                        } else if (error.code === 3) {

                            pesan =
                                'Pengambilan lokasi terlalu lama. Silakan coba kembali.';
                        }


                        statusLokasi.innerHTML =
                            pesan;

                    },

                    {
                        enableHighAccuracy: true,

                        timeout: 15000,

                        maximumAge: 0
                    }

                );
            }


            /*
            |--------------------------------------------------------------------------
            | Kamera
            |--------------------------------------------------------------------------
            */

            async function bukaCamera()
            {
                if (stream) {
                    return;
                }


                try {

                    stream =
                        await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: 'user'
                            },

                            audio: false
                        });


                    video.srcObject =
                        stream;


                } catch (error) {

                    alert(
                        'Kamera tidak dapat dibuka. ' +
                        'Pastikan izin kamera telah diberikan.'
                    );
                }
            }


            function stopCamera()
            {
                if (stream) {

                    stream
                        .getTracks()
                        .forEach(function (track) {
                            track.stop();
                        });


                    stream = null;

                    video.srcObject = null;
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Ambil Selfie
            |--------------------------------------------------------------------------
            */

            btnAmbilFoto.addEventListener(
                'click',
                function () {

                    if (!video.videoWidth) {

                        alert(
                            'Kamera belum siap. Tunggu beberapa saat lalu coba kembali.'
                        );

                        return;
                    }


                    canvas.width =
                        video.videoWidth;

                    canvas.height =
                        video.videoHeight;


                    const context =
                        canvas.getContext('2d');


                    context.drawImage(
                        video,
                        0,
                        0,
                        canvas.width,
                        canvas.height
                    );


                    const foto =
                        canvas.toDataURL(
                            'image/jpeg',
                            0.85
                        );


                    fotoInput.value =
                        foto;

                    preview.src =
                        foto;


                    cameraContainer.style.display =
                        'none';

                    previewContainer.style.display =
                        'block';

                    btnAmbilFoto.style.display =
                        'none';

                    btnUlangiFoto.style.display =
                        'inline-flex';


                    fotoSiap = true;


                    stopCamera();
                }
            );


            /*
            |--------------------------------------------------------------------------
            | Ulangi Selfie
            |--------------------------------------------------------------------------
            */

            btnUlangiFoto.addEventListener(
                'click',
                function () {

                    resetFoto();

                    bukaCamera();

                }
            );


            function resetFoto()
            {
                fotoInput.value =
                    '';

                preview.src =
                    '';

                fotoSiap =
                    false;


                cameraContainer.style.display =
                    'block';

                previewContainer.style.display =
                    'none';

                btnAmbilFoto.style.display =
                    'inline-flex';

                btnUlangiFoto.style.display =
                    'none';
            }


            /*
            |--------------------------------------------------------------------------
            | Validasi Sebelum Submit
            |--------------------------------------------------------------------------
            */

            form.addEventListener(
                'submit',
                function (event) {

                    const pilihan =
                        document.querySelector(
                            'input[name="kehadiran"]:checked'
                        );


                    if (!pilihan) {

                        event.preventDefault();

                        alert(
                            'Silakan pilih status kehadiran terlebih dahulu.'
                        );

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Hadir
                    |--------------------------------------------------------------------------
                    */

                    if (pilihan.value === 'hadir') {

                        if (!lokasiSiap) {

                            event.preventDefault();

                            alert(
                                'Lokasi belum berhasil diperoleh. ' +
                                'Pastikan GPS aktif dan izin lokasi diberikan.'
                            );

                            return;
                        }


                        if (!fotoSiap) {

                            event.preventDefault();

                            alert(
                                'Silakan ambil selfie terlebih dahulu.'
                            );

                            return;
                        }
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Tidak Hadir
                    |--------------------------------------------------------------------------
                    */

                    if (pilihan.value === 'tidak_hadir') {

                        if (!alasanTidakHadir.value) {

                            event.preventDefault();

                            alert(
                                'Silakan pilih alasan tidak hadir.'
                            );

                            return;
                        }


                        if (
                            keterangan.value
                                .trim()
                                .length === 0
                        ) {

                            event.preventDefault();

                            alert(
                                'Silakan isi keterangan tidak hadir.'
                            );

                            return;
                        }
                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Pulihkan Pilihan Setelah Validation Error
            |--------------------------------------------------------------------------
            */

            updatePilihan();


            /*
            |--------------------------------------------------------------------------
            | Stop Kamera Saat Halaman Ditutup
            |--------------------------------------------------------------------------
            */

            window.addEventListener(
                'beforeunload',
                stopCamera
            );

        });

    </script>

</x-app-layout>