<?php
$plain = "admin123";
$hash  = "$2y$10$IDiybO3gKnNTVUFmTukLqOwQ.B.HMJ.ELHBQ24i8uIOGIdZ0yTuy";

var_dump(password_verify($plain, $hash));
