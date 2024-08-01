<?php
// header.php

if (isset($_SESSION['user_id'])):
?>
<header>
    <nav>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<?php endif; ?>
<?php if (isUserSuperAdmin()): ?>
        <!-- Button visible only to super admins -->
        <a href="admin.php">admin</a>
<?php endif; ?>
