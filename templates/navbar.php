<?php
// Sprawdzamy, czy użytkownik jest zalogowany
$isLoggedIn = isset($_SESSION['logged_id']);

// Pobieramy nazwę aktualnie otwartego pliku (np. 'expense.php', 'index.php')
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<header class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
  <div class="container d-flex align-items-center justify-content-between">
    
    <!-- Link z logo wykorzystujący nasz wcześniejszy podział -->
    <a class="navbar-brand d-flex align-items-center my-1" href="<?= $isLoggedIn ? 'index.php' : 'login.php'; ?>">
      <?php include 'templates/logo.php'; ?>
    </a>

    <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
      aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <nav class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto navbar-custom">

        <?php if ($isLoggedIn): ?>
          <!-- MENU DLA ZALOGOWANEGO UŻYTKOWNIKA -->
          
          <li class="nav-item">
            <a class="nav-link px-3 <?= ($currentPage === 'index.php') ? 'active' : ''; ?>" 
               <?= ($currentPage === 'index.php') ? 'aria-current="page"' : ''; ?> 
               href="index.php">Home</a>
          </li>

          <li class="nav-item">
            <a class="nav-link px-3 <?= ($currentPage === 'expense.php') ? 'active' : ''; ?>" 
               <?= ($currentPage === 'expense.php') ? 'aria-current="page"' : ''; ?> 
               href="expense.php">Add Expense</a>
          </li>

          <li class="nav-item">
            <a class="nav-link px-3 <?= ($currentPage === 'income.php') ? 'active' : ''; ?>" 
               <?= ($currentPage === 'income.php') ? 'aria-current="page"' : ''; ?> 
               href="income.php">Add Income</a>
          </li>

          <li class="nav-item">
            <a class="nav-link px-3 <?= ($currentPage === 'balance.php') ? 'active' : ''; ?>" 
               <?= ($currentPage === 'balance.php') ? 'aria-current="page"' : ''; ?> 
               href="balance.php">View Balance</a>
          </li>

          <li class="nav-item">
            <a class="nav-link px-3" href="#">Settings</a>
          </li>

          <li class="nav-item">
            <a class="nav-link px-3" href="logout.php">Logout</a>
          </li>

        <?php else: ?>
          <!-- MENU DLA NIEZALOGOWANEGO UŻYTKOWNIKA (Logowanie / Rejestracja) -->

          <li class="nav-item">
            <a class="nav-link px-3 <?= ($currentPage === 'register.php') ? 'active' : ''; ?>" 
               <?= ($currentPage === 'register.php') ? 'aria-current="page"' : ''; ?> 
               href="register.php">Register</a>
          </li>

          <li class="nav-item">
            <a class="nav-link px-3 <?= ($currentPage === 'login.php') ? 'active' : ''; ?>" 
               <?= ($currentPage === 'login.php') ? 'aria-current="page"' : ''; ?> 
               href="login.php">Login</a>
          </li>

        <?php endif; ?>

      </ul>
    </nav>

  </div>
</header>