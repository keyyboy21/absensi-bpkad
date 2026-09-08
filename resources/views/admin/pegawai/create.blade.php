<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">
            Tambah Pegawai
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4">

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

                <form action="{{ route('admin.pegawai.store') }}"
                      method="POST">

                    @csrf

                    <div class="mb-4">
                        <label>NIP</label>

                        <input type="text"
                               name="nip"
                               value="{{ old('nip') }}"
                               class="w-full mt-1 rounded border-gray-300">

                        @error('nip')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label>Nama Pegawai</label>

                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               class="w-full mt-1 rounded border-gray-300">

                        @error('name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label>Email</label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full mt-1 rounded border-gray-300">

                        @error('email')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label>Jabatan</label>

                        <input type="text"
                               name="jabatan"
                               value="{{ old('jabatan') }}"
                               class="w-full mt-1 rounded border-gray-300">

                        @error('jabatan')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label>Bidang / Unit Kerja</label>

                        <input type="text"
                               name="bidang"
                               value="{{ old('bidang') }}"
                               class="w-full mt-1 rounded border-gray-300">

                        @error('bidang')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label>Password</label>

                        <input type="password"
                               name="password"
                               class="w-full mt-1 rounded border-gray-300">

                        @error('password')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label>Konfirmasi Password</label>

                        <input type="password"
                               name="password_confirmation"
                               class="w-full mt-1 rounded border-gray-300">
                    </div>

                    <div class="flex gap-3">

                        <button
                            class="px-4 py-2 bg-blue-600 text-white rounded">
                            Simpan
                        </button>

                        <a href="{{ route('admin.pegawai.index') }}"
                           class="px-4 py-2 bg-gray-500 text-white rounded">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>

        </div>
    </div>
</x-app-layout>