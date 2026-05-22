# Authentication Topics Reference

This file summarizes authentication-related implementation in the Taskflow Laravel app using real code snippets from the workspace.

---

## 1. User Registration

### Registration form

```blade
<form method="POST" action="{{ route('register') }}">
    @csrf

    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
    <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
</form>
```

### Registration handler / hashing

```php
Validator::make($input, [
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    'password' => $this->passwordRules(),
    'role' => ['nullable', 'string', 'in:admin,manager,user'],
    'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
])->validate();

return User::create([
    'name' => $input['name'],
    'email' => $input['email'],
    'password' => Hash::make($input['password']),
    'role' => $input['role'] ?? 'user',
]);
```

### Registration test

```php
$response = $this->post('/register', [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password',
    'password_confirmation' => 'password',
    'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
]);

$this->assertAuthenticated();
$response->assertRedirect(route('dashboard', absolute: false));
```

---

## 2. Login System

### Login form

```blade
<form method="POST" action="{{ route('login') }}">
    @csrf

    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
</form>
```

### Login test

```php
$response = $this->post('/login', [
    'email' => $user->email,
    'password' => 'password',
]);

$this->assertAuthenticated();
$response->assertRedirect(route('dashboard', absolute: false));
```

### Google OAuth login

```php
$googleUser = Socialite::driver('google')->user();

$user = User::where('email', $googleUser->email)->first();

if (!$user) {
    $user = User::create([
        'name' => $googleUser->name,
        'email' => $googleUser->email,
        'google_id' => $googleUser->id,
        'avatar' => $googleUser->avatar,
        'password' => bcrypt('password123')
    ]);
}

Auth::login($user);

return redirect('/dashboard');
```

---

## 3. Logout System

### Logout button / form

```blade
<form method="POST" action="{{ route('logout') }}" x-data>
    @csrf

    <x-dropdown-link href="{{ route('logout') }}"
             @click.prevent="$root.submit();">
        {{ __('Log Out') }}
    </x-dropdown-link>
</form>
```

---

## 4. Password Hashing

### Password hashing on registration

```php
'password' => Hash::make($input['password']),
```

### Automatic hashed casting in the User model

```php
protected function casts(): array
{
    return [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

### Password hashing in factory data

```php
'password' => static::$password ??= Hash::make('password'),
```

---

## 5. Session Management

### Jetstream authenticated session middleware

```php
'middleware' => ['web'],

'auth_session' => AuthenticateSession::class,
```

### Current authenticated user access

```php
$user = auth()->user();
$userName = $user ? $user->name : 'System';
```

### Guest/authenticated state checks

```php
$this->assertAuthenticated();
$this->assertGuest();
```

---

## 6. Secure Authentication

### User model is an authenticatable model

```php
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
}
```

### Sensitive fields are hidden from serialization

```php
protected $hidden = [
    'password',
    'remember_token',
    'two_factor_recovery_codes',
    'two_factor_secret',
];
```

### CSRF protection on auth forms

```blade
@csrf
```

### Session-based authenticated login

```php
Auth::login($user);
```
