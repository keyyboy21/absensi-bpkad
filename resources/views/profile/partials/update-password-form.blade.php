<section>

    <header>

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Ubah Password
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Gunakan password yang kuat dan tidak mudah ditebak untuk menjaga keamanan akun.
        </p>

    </header>


    <form
        method="post"
        action="{{ route('password.update') }}"
        class="mt-6 space-y-6"
    >

        @csrf
        @method('put')


        {{-- ============================================= --}}
        {{-- PASSWORD SAAT INI --}}
        {{-- ============================================= --}}

        <div>

            <x-input-label
                for="update_password_current_password"
                value="Password Saat Ini"
            />


            <div class="relative">

                <x-text-input
                    id="update_password_current_password"
                    name="current_password"
                    type="password"
                    class="mt-1 block w-full pr-12"
                    autocomplete="current-password"
                />


                <button
                    type="button"
                    onclick="togglePassword(
                        'update_password_current_password',
                        'eye-current-open',
                        'eye-current-closed'
                    )"
                    class="
                        absolute
                        inset-y-0
                        right-0
                        flex
                        items-center
                        px-3
                        text-gray-500
                        dark:text-gray-400
                    "
                >

                    <svg
                        id="eye-current-open"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                        />
                    </svg>


                    <svg
                        id="eye-current-closed"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5 hidden"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3l18 18"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.477 10.477A3 3 0 0 0 14.65 14.65"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.88 5.09A9.91 9.91 0 0 1 12 4.875c6 0 9.75 7.125 9.75 7.125a17.6 17.6 0 0 1-3.06 4.14"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.61 6.61C3.82 8.34 2.25 12 2.25 12S6 19.125 12 19.125a9.9 9.9 0 0 0 3.38-.59"
                        />
                    </svg>

                </button>

            </div>


            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2"
            />

        </div>


        {{-- ============================================= --}}
        {{-- PASSWORD BARU --}}
        {{-- ============================================= --}}

        <div>

            <x-input-label
                for="update_password_password"
                value="Password Baru"
            />


            <div class="relative">

                <x-text-input
                    id="update_password_password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full pr-12"
                    autocomplete="new-password"
                />


                <button
                    type="button"
                    onclick="togglePassword(
                        'update_password_password',
                        'eye-new-open',
                        'eye-new-closed'
                    )"
                    class="
                        absolute
                        inset-y-0
                        right-0
                        flex
                        items-center
                        px-3
                        text-gray-500
                        dark:text-gray-400
                    "
                >

                    <svg
                        id="eye-new-open"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                        />
                    </svg>


                    <svg
                        id="eye-new-closed"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5 hidden"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3l18 18"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.477 10.477A3 3 0 0 0 14.65 14.65"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.88 5.09A9.91 9.91 0 0 1 12 4.875c6 0 9.75 7.125 9.75 7.125a17.6 17.6 0 0 1-3.06 4.14"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.61 6.61C3.82 8.34 2.25 12 2.25 12S6 19.125 12 19.125a9.9 9.9 0 0 0 3.38-.59"
                        />
                    </svg>

                </button>

            </div>


            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2"
            />

        </div>


        {{-- ============================================= --}}
        {{-- KONFIRMASI PASSWORD --}}
        {{-- ============================================= --}}

        <div>

            <x-input-label
                for="update_password_password_confirmation"
                value="Konfirmasi Password Baru"
            />


            <div class="relative">

                <x-text-input
                    id="update_password_password_confirmation"
                    name="password_confirmation"
                    type="password"
                    class="mt-1 block w-full pr-12"
                    autocomplete="new-password"
                />


                <button
                    type="button"
                    onclick="togglePassword(
                        'update_password_password_confirmation',
                        'eye-confirm-open',
                        'eye-confirm-closed'
                    )"
                    class="
                        absolute
                        inset-y-0
                        right-0
                        flex
                        items-center
                        px-3
                        text-gray-500
                        dark:text-gray-400
                    "
                >

                    <svg
                        id="eye-confirm-open"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                        />
                    </svg>


                    <svg
                        id="eye-confirm-closed"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-5 h-5 hidden"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3 3l18 18"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M10.477 10.477A3 3 0 0 0 14.65 14.65"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.88 5.09A9.91 9.91 0 0 1 12 4.875c6 0 9.75 7.125 9.75 7.125a17.6 17.6 0 0 1-3.06 4.14"
                        />

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M6.61 6.61C3.82 8.34 2.25 12 2.25 12S6 19.125 12 19.125a9.9 9.9 0 0 0 3.38-.59"
                        />
                    </svg>

                </button>

            </div>


            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2"
            />

        </div>


        {{-- ============================================= --}}
        {{-- TOMBOL SIMPAN --}}
        {{-- ============================================= --}}

        <div class="flex items-center gap-4">

            <x-primary-button>
                Simpan Password
            </x-primary-button>


            @if (session('status') === 'password-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >
                    Password berhasil diperbarui.
                </p>

            @endif

        </div>

    </form>


    <script>

        function togglePassword(
            inputId,
            eyeOpenId,
            eyeClosedId
        ) {

            const input =
                document.getElementById(inputId);

            const eyeOpen =
                document.getElementById(eyeOpenId);

            const eyeClosed =
                document.getElementById(eyeClosedId);


            if (input.type === 'password') {

                input.type = 'text';

            } else {

                input.type = 'password';

            }


            eyeOpen.classList.toggle('hidden');

            eyeClosed.classList.toggle('hidden');

        }

    </script>

</section>