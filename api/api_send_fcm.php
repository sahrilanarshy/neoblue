<?php
require_once __DIR__ . '/bootstrap.php';
$input = get_json_input();
$target_user_id = isset($input['user_id']) ? intval($input['user_id']) : 0;
$title = $input['title'] ?? 'Pemberitahuan';
$body = $input['body'] ?? '';
$data = $input['data'] ?? [];
if (!$target_user_id || !$body) send_json(400, ['status'=>'error','message'=>'user_id dan body wajib']);
// get token
$res = mysqli_query($koneksi, "SELECT token FROM user_device_tokens WHERE user_id=".(int)$target_user_id." LIMIT 1");
$row = $res ? mysqli_fetch_assoc($res) : null;
if (!$row || !$row['token']) send_json(404, ['status'=>'error','message'=>'token tidak ditemukan']);
$token = $row['token'];
// FCM legacy server key from env/config
$server_key = getenv('FCM_SERVER_KEY');
if (!$server_key) {
  // try config file
  $config_path = __DIR__ . '/../config/fcm.php';
  if (file_exists($config_path)) {
    $cfg = include $config_path; $server_key = $cfg['server_key'] ?? null;
  }
}
if (!$server_key) send_json(500, ['status'=>'error','message'=>'FCM server key belum dikonfigurasi']);
$payload = [
  'to' => $token,
  'notification' => ['title' => $title, 'body' => $body],
  'data' => $data
];
$ch = curl_init('https://fcm.googleapis.com/fcm/send');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
  'Authorization: key=' . $server_key,
  'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
$resp = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);
if ($err) send_json(500, ['status'=>'error','message'=>'curl error','data'=>$err]);
send_json($httpcode, ['status'=> $httpcode===200? 'success':'error', 'message'=>'fcm response', 'data'=> json_decode($resp, true)]);
