<?php
// public/auth/register.php
require_once __DIR__ . '/../../src/includes/auth.php';

if (!empty($_SESSION['user_id'])) {
    header("Location: /dashboard/consignor.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $displayName = trim($_POST['display_name'] ?? '');

    if ($email && $password && $displayName) {
        if (strlen($password) < 8) {
            $error = "Password must be at least 8 characters.";
        } else {
            $result = registerUser($email, $password, $displayName);
            if ($result === true) {
                // Auto-login after registration
                loginUser($email, $password);
                header("Location: /dashboard/consignor.php");
                exit;
            } else {
                $error = $result;
            }
        }
    } else {
        $error = "Please fill in all fields.";
    }
}

$pageTitle = 'Create Account';
require __DIR__ . '/../../src/views/layouts/header.php';
?>

<section class="max-w-md mx-auto px-6 pt-24 pb-16">
  <div class="card-breath p-8 md:p-10">
    <h1 class="text-3xl mb-2 text-center">Join the herd.</h1>
    <p class="text-elephant-500 text-center mb-8 text-sm">Start giving good things another breath.</p>

    <?php if ($error): ?>
      <div class="mb-6 p-4 rounded-xl bg-red-50 text-red-700 text-sm border border-red-100">
        <?= e($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="/auth/register.php" class="space-y-5">
      <div>
        <label class="block text-sm font-medium text-elephant-700 mb-1.5">Display Name</label>
        <input type="text" name="display_name" required class="input-breath" placeholder="Jane Doe">
      </div>
      <div>
        <label class="block text-sm font-medium text-elephant-700 mb-1.5">Email</label>
        <input type="email" name="email" required class="input-breath" placeholder="you@example.com">
      </div>
      <div>
        <label class="block text-sm font-medium text-elephant-700 mb-1.5">Password</label>
        <input type="password" name="password" required class="input-breath" placeholder="Minimum 8 characters">
      </div>
      <button type="submit" class="btn-breath w-full mt-2">Create Account</button>
    </form>

    <p class="mt-8 text-center text-sm text-elephant-500">
      Already have an account? <a href="/auth/login.php" class="text-sage-600 font-medium hover:text-sage-500">Sign in</a>
    </p>
  </div>
</section>

<?php require __DIR__ . '/../../src/views/layouts/footer.php'; ?>