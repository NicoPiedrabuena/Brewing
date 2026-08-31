<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
  <meta name="theme-color" content="#171914">
  <title>Ingresar · Brújula Brew</title>
  <link rel="stylesheet" href="/css/brew.css">
</head>
<body class="login-page">
  <main class="auth-layout">
    <section class="auth-story" aria-label="Brújula Brew Log">
      <div class="auth-story-top">
        <div class="auth-brand">
          <span class="auth-brandmark">B</span>
          <span>BRÚJULA<small>BREW LOG</small></span>
        </div>
        <span class="auth-private">Bitácora privada</span>
      </div>

      <div class="auth-story-copy">
        <span class="auth-kicker">RECETAS · COCCIONES · PROCESO</span>
        <h1>Cada cerveza tiene<br>una <em>historia.</em></h1>
        <p>Guardá cada decisión, medí cada cambio y convertí tus mejores cocciones en recetas que puedas repetir.</p>
      </div>

      <div class="auth-brew-card" aria-hidden="true">
        <div>
          <span>EN FERMENTACIÓN</span>
          <strong>IPA del Patio</strong>
          <small>Lote #L014 · Día 6</small>
        </div>
        <div class="auth-reading"><b>18.5°</b><small>Temperatura</small></div>
        <div class="auth-reading"><b>1.018</b><small>Densidad</small></div>
        <i></i>
      </div>

      <div class="auth-orbit" aria-hidden="true"><span>✣</span></div>
    </section>

    <section class="auth-access">
      <div class="auth-mobile-brand">
        <span class="auth-brandmark">B</span>
        <span>BRÚJULA<small>BREW LOG</small></span>
      </div>

      <div class="auth-form-wrap">
        <span class="eyebrow">ACCESO PRIVADO</span>
        <h2>Bienvenido<br>de vuelta.</h2>
        <p class="auth-intro">Ingresá para continuar con tu próxima gran cerveza.</p>

        @if($errors->any())
          <div class="login-error" role="alert"><span>!</span>{{ $errors->first() }}</div>
        @endif

        <form method="post" action="{{ route('login.store') }}" class="auth-form">
          @csrf
          <label>
            <span>Email</span>
            <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" placeholder="tu@email.com" required autofocus>
          </label>
          <label>
            <span>Contraseña</span>
            <input type="password" name="password" autocomplete="current-password" placeholder="••••••••••••" required>
          </label>
          <label class="remember">
            <input type="checkbox" name="remember" value="1" checked>
            <span>Mantener mi sesión activa</span>
          </label>
          <button class="auth-submit" type="submit"><span>Ingresar a mi bitácora</span><b>→</b></button>
        </form>

        <p class="auth-security"><span>◆</span> Tus datos permanecen en esta instalación privada.</p>
      </div>
    </section>
  </main>
</body>
</html>
