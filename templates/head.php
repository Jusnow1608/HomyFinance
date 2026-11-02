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

  <meta name="title" property="og:title" content="HomyFinance | <?= e($pageTitle ?? 'Home'); ?>">
  <meta property="og:url" content="https://jusnow1608.github.io/HomyFinance/<?= e($pageFileName ?? 'index.php'); ?>">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="icon" type="image/svg+xml" href="images/favicon.svg">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
    rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">

  <title>HomyFinance - <?= e($pageTitle ?? 'Home'); ?></title>

  <!-- Dodatkowe skrypty (np. reCAPTCHA tylko dla rejestracji) -->
  <?= $extraHead ?? ''; ?>

</head>