<?php
require_once __DIR__ . '/bootstrap.php';
$input = get_json_input();
$user_id = isset($input['user_id']) ? intval($input['user_id']) : 0;
$token = isset($input['token']) ? trim($input['token']) : '';
$device = isset($input['device']) ? trim($input['device']) : null; // android/ios
if (!$user_id || !$token) send_json(400, ['status'=>'error','message'=>'user_id dan token wajib']);
// ensure table exists (id, user_id unique, token, device, updated_at)
mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS user_device_tokens (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token VARCHAR(255) NOT NULL,
  device VARCHAR(50) NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
// upsert
$stmt = mysqli_prepare($koneksi, "INSERT INTO user_device_tokens(user_id, token, device) VALUES(?, ?, ?) ON DUPLICATE KEY UPDATE token=VALUES(token), device=VALUES(device)");
mysqli_stmt_bind_param($stmt, 'iss', $user_id, $token, $device);
if (!mysqli_stmt_execute($stmt)) send_json(500, ['status'=>'error','message'=>'gagal simpan token']);
send_json(200, ['status'=>'success','message'=>'token tersimpan']);
