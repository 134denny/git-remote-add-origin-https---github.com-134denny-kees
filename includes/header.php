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
