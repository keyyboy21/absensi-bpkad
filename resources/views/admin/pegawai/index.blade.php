<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between gap-4">

            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">
                Data Pegawai
            </h2>

            <div class="flex items-center gap-3">

                {{-- IMPORT EXCEL --}}
                <form
                    action="{{ route('admin.pegawai.import') }}"
                    method="POST"
                    enctype="multipart/form-data"
                    class="flex items-center gap-2"
                >

                    @csrf

                    <input
                        type="file"
                        name="file"
                        accept=".xlsx,.xls,.csv"
                        required
                        class="
                            block
                            text-sm
                            text-gray-700
                            dark:text-gray-300
                            file:mr-3
                            file:px-3
                            file:py-2
                            file:border-0
                            file:rounded-lg
                            file:bg-green-600
                            file:text-white
                            hover:file:bg-green-700
                            cursor-pointer
                        "
                    >

                    <button
                        type="submit"
                        class="
                            px-4 py-2
                            bg-green-600
                            hover:bg-green-700
                            text-white
                            rounded-lg
                            transition
                            whitespace-nowrap
                        "
                    >
                        Import Excel
                    </button>

                </form>


                {{-- TAMBAH PEGAWAI --}}
                <a
                    href="{{ route('admin.pegawai.create') }}"
                    class="
                        px-4 py-2
                        bg-blue-600
                        hover:bg-blue-700
                        text-white
                        rounded-lg
                        transition
                        whitespace-nowrap
                    "
                >
                    + Tambah Pegawai
                </a>

            </div>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto px-4">


            {{-- NOTIFIKASI SUKSES --}}
            @if(session('success'))

                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>

            @endif


            {{-- NOTIFIKASI ERROR --}}
            @if($errors->any())

                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-lg">

                    <ul class="list-disc list-inside">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- SEARCH & FILTER --}}
            <div
                class="
                    mb-6
                    bg-white
                    dark:bg-gray-800
                    shadow
                    rounded-lg
                    p-4
                "
            >

                <form
                    method="GET"
                    action="{{ route('admin.pegawai.index') }}"
                    class="
                        grid
                        grid-cols-1
                        md:grid-cols-4
                        gap-4
                    "
                >

                    {{-- Search --}}
                    <div class="md:col-span-2">

                        <label
                            for="search"
                            class="
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                                mb-1
                            "
                        >
                            Cari Pegawai
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama atau NIP..."
                            class="
                                w-full
                                rounded-lg
                                border-gray-300
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-gray-200
                                focus:border-indigo-500
                                focus:ring-indigo-500
                            "
                        >

                    </div>


                    {{-- Filter Bidang --}}
                    <div>

                        <label
                            for="bidang"
                            class="
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                                mb-1
                            "
                        >
                            Bidang
                        </label>

                        <select
                            id="bidang"
                            name="bidang"
                            class="
                                w-full
                                rounded-lg
                                border-gray-300
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-gray-200
                                focus:border-indigo-500
                                focus:ring-indigo-500
                            "
                        >

                            <option value="">
                                Semua Bidang
                            </option>

                            @foreach($bidangList as $bidang)

                                <option
                                    value="{{ $bidang }}"
                                    @selected(request('bidang') === $bidang)
                                >
                                    {{ $bidang }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Filter Status --}}
                    <div>

                        <label
                            for="status"
                            class="
                                block
                                text-sm
                                font-medium
                                text-gray-700
                                dark:text-gray-300
                                mb-1
                            "
                        >
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            class="
                                w-full
                                rounded-lg
                                border-gray-300
                                dark:border-gray-700
                                dark:bg-gray-900
                                dark:text-gray-200
                                focus:border-indigo-500
                                focus:ring-indigo-500
                            "
                        >

                            <option value="">
                                Semua Status
                            </option>

                            <option
                                value="aktif"
                                @selected(request('status') === 'aktif')
                            >
                                Aktif
                            </option>

                            <option
                                value="nonaktif"
                                @selected(request('status') === 'nonaktif')
                            >
                                Nonaktif
                            </option>

                        </select>

                    </div>


                    {{-- Tombol --}}
                    <div
                        class="
                            md:col-span-4
                            flex
                            justify-end
                            gap-2
                        "
                    >

                        <a
                            href="{{ route('admin.pegawai.index') }}"
                            class="
                                px-4 py-2
                                bg-gray-500
                                hover:bg-gray-600
                                text-white
                                rounded-lg
                                transition
                            "
                        >
                            Reset
                        </a>


                        <button
                            type="submit"
                            class="
                                px-4 py-2
                                bg-indigo-600
                                hover:bg-indigo-700
                                text-white
                                rounded-lg
                                transition
                            "
                        >
                            Cari / Filter
                        </button>

                    </div>

                </form>

            </div>


            {{-- TABEL --}}
            <div
                class="
                    bg-white
                    dark:bg-gray-800
                    shadow
                    rounded-lg
                    overflow-hidden
                "
            >

                <div class="overflow-x-auto">

                    <table
                        class="
                            w-full
                            text-sm
                            text-gray-700
                            dark:text-gray-200
                        "
                    >

                        <thead
                            class="
                                bg-gray-100
                                dark:bg-gray-700
                                text-gray-800
                                dark:text-gray-100
                            "
                        >

                            <tr>

                                <th class="p-4 text-left font-semibold">
                                    No
                                </th>

                                <th class="p-4 text-left font-semibold">
                                    NIP
                                </th>

                                <th class="p-4 text-left font-semibold">
                                    Nama
                                </th>

                                <th class="p-4 text-left font-semibold">
                                    Email
                                </th>

                                <th class="p-4 text-left font-semibold">
                                    Jabatan
                                </th>

                                <th class="p-4 text-left font-semibold">
                                    Bidang
                                </th>

                                <th class="p-4 text-left font-semibold">
                                    Status
                                </th>

                                <th class="p-4 text-center font-semibold">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">

                            @forelse($pegawai as $item)

                                <tr
                                    class="
                                        bg-white
                                        dark:bg-gray-800
                                        hover:bg-gray-50
                                        dark:hover:bg-gray-700
                                        transition
                                    "
                                >

                                    {{-- Nomor pagination --}}
                                    <td class="p-4 text-gray-700 dark:text-gray-300">
                                        {{ $pegawai->firstItem() + $loop->index }}
                                    </td>


                                    {{-- NIP --}}
                                    <td
                                        class="
                                            p-4
                                            text-gray-700
                                            dark:text-gray-300
                                            whitespace-nowrap
                                        "
                                    >
                                        {{ $item->nip }}
                                    </td>


                                    {{-- Nama --}}
                                    <td
                                        class="
                                            p-4
                                            font-semibold
                                            text-gray-900
                                            dark:text-white
                                        "
                                    >
                                        {{ $item->name }}
                                    </td>


                                    {{-- Email --}}
                                    <td class="p-4 text-gray-700 dark:text-gray-300">

                                        @if($item->email)

                                            {{ $item->email }}

                                        @else

                                            <span class="text-gray-400">
                                                -
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Jabatan --}}
                                    <td class="p-4 text-gray-700 dark:text-gray-300">

                                        {{ $item->jabatan ?: '-' }}

                                    </td>


                                    {{-- Bidang --}}
                                    <td class="p-4 text-gray-700 dark:text-gray-300">

                                        {{ $item->bidang ?: '-' }}

                                    </td>


                                    {{-- Status --}}
                                    <td class="p-4">

                                        @if($item->status === 'aktif')

                                            <span
                                                class="
                                                    inline-flex
                                                    px-3 py-1
                                                    bg-green-100
                                                    text-green-700
                                                    font-semibold
                                                    rounded-md
                                                "
                                            >
                                                Aktif
                                            </span>

                                        @else

                                            <span
                                                class="
                                                    inline-flex
                                                    px-3 py-1
                                                    bg-red-100
                                                    text-red-700
                                                    font-semibold
                                                    rounded-md
                                                "
                                            >
                                                Nonaktif
                                            </span>

                                        @endif

                                    </td>


                                    {{-- Aksi --}}
                                    <td class="p-4">

                                        <div class="flex gap-2 justify-center">

                                            <a
                                                href="{{ route('admin.pegawai.edit', $item->id) }}"
                                                class="
                                                    px-3 py-1.5
                                                    bg-yellow-500
                                                    hover:bg-yellow-600
                                                    text-white
                                                    font-medium
                                                    rounded-md
                                                    transition
                                                "
                                            >
                                                Edit
                                            </a>


                                            <form
                                                action="{{ route('admin.pegawai.destroy', $item->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus pegawai ini?')"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="
                                                        px-3 py-1.5
                                                        bg-red-600
                                                        hover:bg-red-700
                                                        text-white
                                                        font-medium
                                                        rounded-md
                                                        transition
                                                    "
                                                >
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>


                            @empty

                                <tr>

                                    <td
                                        colspan="8"
                                        class="
                                            p-6
                                            text-center
                                            text-gray-500
                                            dark:text-gray-400
                                        "
                                    >
                                        Data pegawai tidak ditemukan.
                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>


            {{-- PAGINATION --}}
            @if($pegawai->hasPages())

                <div class="mt-6">
                    {{ $pegawai->links() }}
                </div>

            @endif


            {{-- INFO JUMLAH DATA --}}
            <div
                class="
                    mt-3
                    text-sm
                    text-gray-500
                    dark:text-gray-400
                "
            >

                @if($pegawai->total() > 0)

                    Menampilkan
                    {{ $pegawai->firstItem() }}
                    -
                    {{ $pegawai->lastItem() }}
                    dari
                    {{ $pegawai->total() }}
                    pegawai.

                @else

                    Tidak ada data pegawai.

                @endif

            </div>

        </div>

    </div>

</x-app-layout>