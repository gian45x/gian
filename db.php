
<?php
$pdo = new PDO("mysql:host=localhost;dbname=my_portfolio", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);