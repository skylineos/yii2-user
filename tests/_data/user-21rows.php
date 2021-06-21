<?php

$users = [];

for ($i = 1; $i < 22; $i++) {
    $users["user$i"] = [
        'name' => "John Doe $i",
        'email' => "johndoe$i@skylinenet.net",
        'passwordHash' => '$2y$13$2AhSLq7.OCvzgCLIa7OVX.XaUpjerPJngMz6yKE4P.J5yY7aQZud.', // "temporary"
        'modifiedBy' => 1,
        'lastModified' => '2020-01-01 12:00:00',
        'dateCreated' => '2020-01-01 12:00:00',
    ];
}

return $users;
