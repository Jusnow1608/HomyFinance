<?php

session_start();

// Guard: Jeśli użytkownik NIE jest zalogowany, przekieruj do logowania
if (!isset($_SESSION['logged_id'])) {
    header('Location: login.php');
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

  <meta name="title" property="og:title" content="HomyFinance | Homepage">
  <meta property="og:url" content="https://jusnow1608.github.io/HomyFinance/index.php">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

  <title>HomyFinance - Homepage</title>


</head>

<body class="body-custom d-flex flex-column min-vh-100">

  <!-- Logo & Navbar-->
  <?php include 'templates/navbar.php' ?>

  <main class="flex-grow-1 d-flex flex-column">

    <!-- Hero -->
    <section class="container-xxl px-3 py-3 my-2 text-center d-flex flex-column flex-grow-1 justify-content-center">

      <header class="my-2 my-md-3 my-lg-4 py-lg-3">
        <h1 class="display-6 fw-bold text-body-emphasis mb-1 mb-md-2 fs-3">Welcome, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User', ENT_QUOTES, 'UTF-8'); ?>!
        </h1>
        <p class="h6 text-muted mb-3">Your personal home budget management center.</p>
      </header>

      <div class="row g-3 g-lg-4 mb-4 mb-lg-5 justify-content-center">

        <div class="col-12 col-md-6 col-lg-4">
          <article class="card card-expense h-100 shadow-sm rounded-4 text-center p-3 p-xl-4">
            <div class="card-body d-flex flex-column align-items-center p-1 p-md-2 p-lg-3">
              <div class="d-flex align-items-center gap-2 mb-3">
                <div
                  class="rounded-circle expense-circle p-2 d-inline-flex align-items-center justify-content-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                    class="bi bi-dash-circle-fill" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1z" />
                  </svg>
                </div>
                <h2 class="h5 fw-bold mb-0 text-dark">Expense</h2>
              </div>
              <p class="card-text text-muted small mb-3 d-md-block">Did you go shopping, pay bills, or buy something
                new? Record
                the expense.</p>
              <a href="expense.php" class="btn btn-danger-custom w-100 mt-auto fw-bold rounded-3 py-2">Add Expense</a>
            </div>
          </article>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <article class="card card-income h-100 shadow-sm rounded-4 text-center p-3 p-xl-4">
            <div class="card-body d-flex flex-column align-items-center p-1 p-md-2 p-lg-3">
              <div class="d-flex align-items-center gap-2 mb-3">
                <div
                  class="rounded-circle income-circle p-2 d-inline-flex align-items-center justify-content-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                    class="bi bi-plus-circle-fill" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                    <path
                      d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3z" />
                  </svg>
                </div>
                <h2 class="h5 fw-bold mb-0 text-dark">Income</h2>
              </div>
              <p class="card-text text-muted small mb-3 d-md-block">Just received your paycheck or earned some extra
                money?
                Record the income.</p>
              <a href="income.php" class="btn btn-success-custom w-100 mt-auto fw-bold rounded-3 py-2">Add Income</a>
            </div>
          </article>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
          <article class="card card-balance h-100 shadow-sm rounded-4 text-center p-3 p-xl-4">
            <div class="card-body d-flex flex-column align-items-center p-1 p-md-2 p-lg-3">
              <div class="d-flex align-items-center gap-2 mb-3">
                <div
                  class="rounded-circle balance-circle p-2 d-inline-flex align-items-center justify-content-center flex-shrink-0">
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor"
                    class="bi bi-pie-chart" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                    <path
                      d="M7.5 1.018a7 7 0 1 0 7.481 7.481A.5.5 0 0 0 14.5 8h-6a.5.5 0 0 1-.5-.5V1.5a.5.5 0 0 0-.5-.482zM8 0a8 8 0 1 1-8 8A8 8 0 0 1 8 0z" />
                    <path d="M8.5 1.5v6.5H15A6.5 6.5 0 0 0 8.5 1.5z" />
                  </svg>
                </div>
                <h2 class="h5 fw-bold mb-0 text-dark">Balance</h2>
              </div>
              <p class="card-text text-muted small mb-3 d-md-block">View a summary of your finances, spending charts,
                and your
                final balance.</p>
              <a href="balance.php" class="btn btn-outline-custom w-100 mt-auto fw-bold rounded-3 py-2">View
                Balance</a>
            </div>
          </article>
        </div>

      </div>

      <aside class="row justify-content-center">
        <div class="col-10 col-sm-8 col-md-6">
          <div class="card border-0 shadow-sm rounded-4 text-center p-1">
            <div class="card-body d-flex flex-row align-items-center text-start p-2 p-lg-3">

              <div
                class="rounded-circle tip-circle p-2 d-inline-flex align-items-center justify-content-center flex-shrink-0">

                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"
                  aria-hidden="true" focusable="false">
                  <path
                    d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                </svg>
              </div>

              <div class="text-start px-3">
                <span class="fw-bold d-block small">Tip of the day</span>
                <small class="text-muted" id="daily-tip">"Small daily savings lead to a big annual balance!"</small>
              </div>
            </div>
          </div>
        </div>
      </aside>

    </section>

  </main>

  <!-- Footer -->
  <?php include 'templates/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>

  <script src="js/tips.js" defer></script>
</body>

</html>