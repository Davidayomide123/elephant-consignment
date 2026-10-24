<?php
// public/auth/login.php
require_once __DIR__ . '/../../src/includes/auth.php';

// Redirect if already logged in
if (!empty($_SESSION['user_id'])) {
    $redirect = $_SESSION['role'] === 'admin' ? '/dashboard/admin.php' : '/dashboard/consignor.php';
    header("Location: $redirect");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $result = loginUser($email, $password);
        if ($result === true) {
            $redirect = $_SESSION['role'] === 'admin' ? '/dashboard/admin.php' : '/dashboard/consignor.php';
            header("Location: $redirect");
            exit;
        } else {
            $error = $result;
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

$pageTitle = 'Sign In';
require __DIR__ . '/../../src/views/layouts/header.php';
?>

<section class="max-w-md mx-auto px-6 pt-24 pb-16">
  <div class="card-breath p-8 md:p-10">
    <h1 class="text-3xl mb-2 text-center">Welcome back.</h1>
    <p class="text-elephant-500 text-center mb-8 text-sm">Breathe in. Log in. Breathe out.</p>

    <?php if ($error): ?>
      <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 text-sm border border-red-100">
        <?= e($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="/auth/login.php" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-elephant-700 mb-1.5">Email</label>
        <input type="email" name="email" required class="input-breath" placeholder="you@example.com">
      </div>
      <div>
        <label class="block text-sm font-medium text-elephant-700 mb-1.5">Password</label>
        <input type="password" name="password" required class="input-breath" placeholder="••••••••">
      </div>
      <button type="submit" class="btn-breath w-full mt-2">Sign In</button>
    </form>

    <p class="mt-8 text-center text-sm text-elephant-500">
      New here? <a href="/auth/register.php" class="text-sage-600 font-medium hover:text-sage-500">Create an account</a>
    </p>
  </div>
</section>

<?php require __DIR__ . '/../../src/views/layouts/footer.php'; ?>