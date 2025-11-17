<?php
// Admin logout

session_destroy();
redirect(url(getenv('ADMIN_PATH')));

