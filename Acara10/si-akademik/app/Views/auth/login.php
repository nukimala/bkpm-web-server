<?php $title = 'Login'; ?>
<h1>Login</h1>
<?php $msg = flash(); ?>
<?php if ($msg !== ''): ?>
    <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
<?php endif; ?>
<form action="<?= BASE_PATH ?>/auth" method="POST" class="mt-3" style="max-width: 420px;">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>