<?php
$this->path = '/';
ini_set('session.cookie_path', $this->path);

// Session cookie now persists across all subdomains
ini_set('session.cookie_domain', env('HTTP_BASE'));


?>
