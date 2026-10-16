<?php

session_start();

// Guard: Jeśli użytkownik NIE jest zalogowany, przekieruj do logowania
if (!isset($_SESSION['logged_id'])) {
    header('Location: login.php');
    exit();
}

$config = require 'config.php';
require_once 'database.php';

try {
    $db = getDatabaseConnection($config);
    $userId = $_SESSION['logged_id'];

    // Pobieranie kategorii przychodów użytkownika
    $catStmt = $db->prepare('SELECT id, name FROM incomes_category_assigned_to_users WHERE user_id = :userId');
    $catStmt->bindValue(':userId', $userId, PDO::PARAM_INT);
    $catStmt->execute();
    $incomeCategories = $catStmt->fetchAll();

} catch (PDOException $e) {
    error_log('Income View Error: ' . $e->getMessage() . PHP_EOL, 3, __DIR__ . '/my_errors.log');
    $incomeCategories = [];
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

  <meta name="title" property="og:title" content="HomyFinance | Income">
  <meta property="og:url" content="https://jusnow1608.github.io/HomyFinance/income.php">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

  <title>HomyFinance - Income</title>
</head>

<body class="body-custom d-flex flex-column min-vh-100">

  <!-- Navbar -->
    <?php include 'templates/navbar.php' ?>

  <main class="flex-grow-1 d-flex flex-column">

    <!-- Main Form Section -->
    <section class="flex-grow-1 d-flex align-items-center py-3">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-md-11 col-lg-10 col-xl-9">

            <div class="card border-0 shadow-sm rounded-4 p-2 p-md-3">
              <div class="card-body-custom">

                <!-- Title Icon -->
                <div class="d-flex align-items-center justify-content-center gap-2 mb-2 mb-md-3">
                  <div
                    class="rounded-circle income-circle p-2 d-inline-flex align-items-center justify-content-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                      class="bi bi-plus-circle-fill" viewBox="0 0 16 16" aria-hidden="true" focusable="false">
                      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M8.5 4.5a.5.5 0 0 0-1 0v3h-3a.5.5 0 0 0 0 1h3v3a.5.5 0 0 0 1 0v-3h3a.5.5 0 0 0 0-1h-3v-3z" />
                    </svg>
                  </div>
                  <h1 class="h4 fw-bold mb-0">Income</h1>
                </div>

                <?php if (isset($_SESSION['e_income'])): ?>
                <div class="alert alert-danger text-center small py-2 mb-3" role="alert">
                  <?= htmlspecialchars($_SESSION['e_income'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <?php unset($_SESSION['e_income']); ?>
              <?php endif; ?>

              <?php if (isset($_SESSION['income_success'])): ?>
                <div class="alert alert-success text-center small py-2 mb-3" role="alert">
                  <?= htmlspecialchars($_SESSION['income_success'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <?php unset($_SESSION['income_success']); ?>
              <?php endif; ?>

                <form id="incomeForm" method="post" action="income_script.php" novalidate>
                  <!-- 1. Amount Input -->
                  <div class="row g-2 mb-3">
                    <div class="col-12 col-md-6">
                      <label for="amount" class="form-label fw-bold small">Amount</label>
                      <div class="input-group input-group-custom">
                        <span class="icon-custom">
                          <i class="bi bi-wallet2"></i>
                        </span>
                        <input type="number" step="0.01" min="0.01" class="form-control form-control-custom" id="incomeAmount"
                          name="amount" aria-describedby="currency-addon" placeholder="0.00" 
                          value="<?= htmlspecialchars($_SESSION['given_income_amount'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                        <span class="icon-custom" id="currency-addon">PLN</span>
                      </div>
                    </div>

                    <!-- 2. Date Input -->
                    <div class="col-12 col-md-6">
                      <label for="date" class="form-label fw-bold small">Date</label>
                      <div class="input-group input-group-custom">
                        <span class="icon-custom">
                          <i class="bi bi-calendar-event"></i>
                        </span>
                        <input type="date" class="form-control form-control-custom" id="incomeDate" name="incomeDate"
                          value="<?= htmlspecialchars($_SESSION['given_income_date'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" required>
                      </div>
                    </div>
                  </div>

                    <!-- 3. Category (Radio Buttons) -->
                  <fieldset class="mb-3 border-0 p-0 m-0">
                    <legend class="form-label fw-bold small d-block">Category</legend>
                    <div class="row g-2">

                    <?php foreach ($incomeCategories as $index => $cat): 
                    $isChecked = (!isset($_SESSION['given_income_category']) && $index === 0) || (isset($_SESSION['given_income_category']) && $_SESSION['given_income_category'] == $cat['id']);
                    ?>
                    <div class="col-6 col-sm-4 col-md-3">
                      <input type="radio" class="btn-check" name="category" id="cat-<?= $cat['id']; ?>" value="<?= $cat['id']; ?>" <?= $isChecked ? 'checked' : ''; ?>>
                      <label class="btn form-select-tile w-100 text-truncate" for="cat-<?= $cat['id']; ?>" title="<?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?>">
                        <span class="tile-text-custom"> <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8'); ?></span>
                      </label>
                    </div>
                    <?php endforeach; ?>

                    </div>
                  </fieldset>

                  <!-- 5. Comment / Note -->
                  <div class="mb-3">
                    <label for="comment" class="form-label fw-bold small">Comment (optional)</label>
                    <textarea class="form-control form-control-custom" id="comment" name="comment" rows="1" maxlength="100"
                      placeholder="Where did this income come from?"><?= htmlspecialchars($_SESSION['given_income_comment'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
                  </div>

                  <!-- Submit Buttons -->
                  <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-success-custom btn-md py-2 w-100 fw-bold">Add Income</button>
                    <button type="reset" class="btn btn-outline-secondary btn-md py-2 fw-bold rounded-3">Cancel</button>
                  </div>

                </form>
                
                <?php 
                unset($_SESSION['given_income_amount']);
                unset($_SESSION['given_income_date']);
                unset($_SESSION['given_income_category']);
                unset($_SESSION['given_income_comment']);
                ?>

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
  <script src="js/date.js" defer></script>
</body>

</html>