<?php
if (file_exists("/fail")){
    http_response_code(500);
} 
?>
<p>Date: <?= date('c') ?></p>
<p>Host: <?= gethostname() ?></p>
<p>id: <?= uniqid() ?></p>
