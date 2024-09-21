<x-guest-layout>
    <div class="whole-container">
        <div class="title-block">
            <div class="text-center"><b>DOST Indicators</b></div>
            <div class="text-center"><b>Management Tool</b></div>
        </div>

        <div class="form-block">
            <div class="form-container">
                <div class="text-welcome">Welcome</div>

                <div class="text-enter"><i>Enter your email address and password to sign in</i></div>

                <div class="input-container">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="fields-container">
                            <!-- Email and Password -->
                            <div class="email-container">
                                <input  id="email"
                                        type="email" name="email"
                                        :value="old('email')"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        placeholder="Enter your email address here"
                                />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="pw-container">
                                <input  id="password"
                                        type="password"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        placeholder="Enter your password here"
                                />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Remember Me -->
                            <div class="remember-container">
                                <div class="checkbox-container"><input type="checkbox" name="remember"></div>
                                <div class="label-remember"><label for="remember_me">Remember me</label></div>
                            </div>

                            <!-- Submit Button -->
                            <div class="submit-container">
                                <input type="submit" class="input-submit" value="SIGN IN">
                            </div>
                        </div>
                    </form>
                </div>

                <div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="action-forgot-pw">Forgot password</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
