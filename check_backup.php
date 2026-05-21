<?php
$data = json_decode(file_get_contents('db_backup.json'), true);
echo "buku keys: " . implode(',', array_keys($data['buku'][0] ?? [])) . "\n";
echo "peminjamans keys: " . implode(',', array_keys($data['peminjamans'][0] ?? [])) . "\n";
