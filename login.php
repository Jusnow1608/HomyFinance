<?php

session_start();

// Jeśli użytkownik jest już zalogowany, przekieruj do index.php
if (isset($_SESSION['logged_id'])) {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description"
    content="HomyFinance is your personal home budget management center. Track expenses, incomes, and control your financial balance easily.">
  <meta name="author" content="Justyna Nowak-Szrajnert">
  <meta property="og:description"
    content="Take full control of your household budget with HomyFinance. Track your incomes and expenses effortlessly while analyzing clear financial summaries.">
  <meta property="og:type" content="website">
  <meta property="og:image" content="https://jusnow1608.github.io/HomyFinance/solution/home.png">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="627">

  <meta name="title" property="og:title" content="HomyFinance | Log In">
  <meta property="og:url" content="https://jusnow1608.github.io/HomyFinance/login.php">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

  <title>HomyFinance - Log In</title>


</head>

<body class="body-custom d-flex flex-column min-vh-100">
  <!-- Logo & Navbar -->
    <?php include 'templates/navbar.php' ?>

  <main class="d-flex flex-column flex-grow-1">

    <!-- Login -->
    <section id="login-form" class="flex-grow-1 d-flex align-items-center py-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-6 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4">
              <div class="card-body">
                <h1 class="h2 fw-bold text-center mb-2">Log In</h1>
                <p class="text-muted text-center mb-4">Log in to your HomyFinance account!</p>

                <!-- Komunikat o sukcesie po rejestracji -->
                <?php if (isset($_SESSION['register_success'])): ?>
                  <div class="alert alert-success text-center small py-2 mb-3" role="alert">
                    <?= $_SESSION['register_success']; ?>
                  </div>
                  <?php unset($_SESSION['register_success']); ?>
                <?php endif; ?>

                <!-- Komunikat o błędzie logowania -->
                <?php if (isset($_SESSION['e_login'])): ?>
                  <div class="alert alert-danger text-center small py-2 mb-3" role="alert">
                    <?= $_SESSION['e_login']; ?>
                  </div>
                  <?php unset($_SESSION['e_login']); ?>
                <?php endif; ?>

                <form method="post" action="login_script.php" novalidate>
                  <label for="email" class="form-label-custom">Email address</label>
                  <div class="input-group mb-3 input-group-custom">

                    <span class="form-control-custom icon-custom">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16" aria-hidden="true">
                        <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414z" />
                        <path
                          d="M0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z" />
                      </svg>
                    </span>

                    <input type="email" class="form-control form-control-custom form-control-md" name="email" id="email"
                      autocomplete="username" placeholder="email@example.com" value="<?= $_SESSION['given_login_email'] ?? '' ?>" aria-label="Email address" required>
                  </div>

                  <label for="password" class="form-label-custom">Password</label>
                  <div class="input-group mb-3 input-group-custom">

                    <span class="form-control-custom icon-custom">
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        viewBox="0 0 16 16" aria-hidden="true">
                        <path
                          d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2" />
                      </svg>
                    </span>

                    <input type="password" class="form-control form-control-custom form-control-md" name="password"
                      id="password" autocomplete="current-password" placeholder="••••••••" aria-label="Password"
                      required>
                  </div>

                  <button type="submit" class="btn btn-success-custom btn-lg w-100 fw-bold mb-3">Log
                    In</button>
                </form>

                <?php unset($_SESSION['given_login_email']); ?>

                <div class="text-center">
                  <span class="text-muted">Don't have an account?</span>
                  <a href="register.php" class="text-success-custom fw-bold">Sign
                    Up</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

  </main>

  <!-- Footer -->
  <?php include 'templates/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

</body>

</html>