<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Company Link Manager - Login</title>

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #abcdef;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-container {
      background: #fff;
      width: 100%;
      max-width: 430px;
      padding: 35px;
      border-radius: 12px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    }

    .icon-wrapper {
      width: 55px;
      height: 55px;
      background: #123456;
      color: white;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      margin: auto;
      font-size: 26px;
      margin-bottom: 20px;
    }

    h1 {
      text-align: center;
      margin-bottom: 5px;
      font-size: 20px;
    }

    .subtitle {
      text-align: center;
      color: #666;
      margin-bottom: 25px;
    }

    label {
      font-weight: bold;
      color: #444;
      display: block;
      margin-bottom: 6px;
    }

    input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 14px;
      margin-bottom: 15px;
    }

    input:focus {
      outline: none;
      border-color: #123456;
      box-shadow: 0 0 3px #123456;
    }

    .btn {
      width: 100%;
      background: #123456;
      color: white;
      border: none;
      padding: 14px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      margin-top: 5px;
    }

    .btn:hover {
      opacity: 0.9;
    }

    .demo-box {
      margin-top: 25px;
      background: #abcdef;
      padding: 15px;
      border-radius: 10px;
      font-size: 14px;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>

<body>

  <div class="login-container">
    
    <!-- LOGIN ICON -->
    <div class="icon-wrapper">➡️</div>

    <h1>Company Link Manager</h1>
    <p class="subtitle">Sign in to access your dashboard</p>

     <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
        {{-- Email --}}
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="your.email@company.com" required autofocus autocomplete="username" value="{{ old('email') }}" />
            @error('email')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

      {{-- Password --}}
        <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Enter your password" required autocomplete="current-password" />
            @error('password')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>
      
      

       {{-- Error --}}
        @if(session('error'))
            <div class="text-red-600 text-center text-sm">{{ session('error') }}</div>
        @endif

      <button type="submit" class="btn">Sign In</button>
    </form>


    <!-- Footer Links -->
        <div class="mt-8 text-center text-sm text-gray-600">
            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:underline">Forgot your password?</a>
            <span class="mx-2 text-gray-400">·</span>
            <a href="mailto:support@company.com" class="text-indigo-600 hover:underline">Need help?</a>
        </div>

  </div>

</body>
</html>
