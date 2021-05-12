<?php

$users = [];

for ($i = 1; $i < 22; $i++) {
    $users["user$i"] = [
        'name' => "John Doe $i",
        'email' => "johndoe$i@skylinenet.net",
        'passwordHash' => '$2y$13$WSyE5hHsG1rWN2jV8LRHzubilrCLI5Ev/iK0r3jRuwQEs2ldRu.a2',
        'modifiedBy' => 1,
        'lastModified' => '2020-01-01 12:00:00',
        'dateCreated' => '2020-01-01 12:00:00',
    ];
}

return $users;
