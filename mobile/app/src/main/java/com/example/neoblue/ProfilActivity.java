package com.example.neoblue;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import androidx.annotation.NonNull;
import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.WindowCompat;

import com.bumptech.glide.Glide;
import com.bumptech.glide.load.engine.DiskCacheStrategy;
import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.ProfileResponse;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import java.io.File;
import java.io.IOException;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class ProfilActivity extends AppCompatActivity {

    private TextView tvStatusUser;
    private EditText etNama, etEmail, etNomorHp, etPasswordLama, etPasswordBaru;
    private Button btnSimpan, btnKeluar, btnGantiFoto;
    private ImageView ivProfilePhoto;
    private String userId;
    private ProgressDialog progressDialog;
    private Uri selectedImageUri;

    private final ActivityResultLauncher<Intent> imagePickerLauncher = registerForActivityResult(
            new ActivityResultContracts.StartActivityForResult(),
            result -> {
                if (result.getResultCode() == Activity.RESULT_OK && result.getData() != null) {
                    selectedImageUri = result.getData().getData();
                    if (selectedImageUri != null) {
                        // Tampilkan preview sementara
                        Glide.with(this).load(selectedImageUri).circleCrop().into(ivProfilePhoto);
                        // Langsung upload foto
                        uploadPhoto();
                    }
                }
            }
    );

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        WindowCompat.setDecorFitsSystemWindows(getWindow(), false);
        setContentView(R.layout.activity_profil);

        setupHeader();

        // Inisialisasi Views
        tvStatusUser = findViewById(R.id.tv_status_user);
        etNama = findViewById(R.id.et_nama_profil);
        etEmail = findViewById(R.id.et_email_profil);
        etNomorHp = findViewById(R.id.et_nomor_hp);
        etPasswordLama = findViewById(R.id.et_password_lama);
        etPasswordBaru = findViewById(R.id.et_password_baru);
        btnSimpan = findViewById(R.id.btn_simpan_profil);
        btnKeluar = findViewById(R.id.btn_keluar);
        ivProfilePhoto = findViewById(R.id.iv_profile_photo);
        btnGantiFoto = findViewById(R.id.btn_ganti_foto);

        progressDialog = new ProgressDialog(this);
        progressDialog.setMessage("Mohon tunggu...");
        progressDialog.setCancelable(false);

        // Ambil User ID dari SharedPreferences
        SharedPreferences preferences = getSharedPreferences("user_session", MODE_PRIVATE);
        userId = preferences.getString("user_id", "");

        if (userId.isEmpty()) {
            // Jika tidak ada session, kembalikan ke Login
            logout();
            return;
        }

        // Load data profil
        loadProfile();

        // 1. Tombol Keluar (Logout)
        btnKeluar.setOnClickListener(v -> showLogoutConfirmation());

        // 2. Tombol Simpan Profil
        btnSimpan.setOnClickListener(v -> updateProfile());
        
        // 3. Tombol Ganti Foto
        btnGantiFoto.setOnClickListener(v -> openImagePicker());

        // 3a. Gabung Komunitas (Premium only)
        Button btnGabungKomunitas = findViewById(R.id.btn_gabung_komunitas);
        if (btnGabungKomunitas != null) {
            btnGabungKomunitas.setOnClickListener(v -> {
                String tipe = tvStatusUser != null ? tvStatusUser.getText().toString().toLowerCase() : "free";
                if (tipe.contains("premium")) {
                    Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse("https://chat.whatsapp.com/G5ijCvPnoD26Tc1FNngupF"));
                    startActivity(intent);
                } else {
                    Toast.makeText(ProfilActivity.this, "Gabung komunitas khusus pengguna Premium", Toast.LENGTH_SHORT).show();
                }
            });
        }

        // 4. Setup Bottom Navigation
        setupBottomNavigation();
    }

    @Override
    protected void onResume() {
        super.onResume();
        com.example.neoblue.utils.NotificationUtils.updateBadgeFromPrefs(this);
    }

    private void setupHeader() {
        ImageView btnNotif = findViewById(R.id.btn_notifikasi);
        if (btnNotif != null) {
            btnNotif.setOnClickListener(v -> startActivity(new Intent(ProfilActivity.this, NotifikasiActivity.class)));
        }

        ImageView btnProfil = findViewById(R.id.btn_profil);
        if (btnProfil != null) {
            // Sudah di halaman profil
        }
        // header view is present in layout; no dynamic inset handling
    }

    private void setupBottomNavigation() {
        BottomNavigationView bottomNav = findViewById(R.id.bottom_navigation_profile);
        if (bottomNav != null) {
            bottomNav.setOnNavigationItemSelectedListener(item -> {
                int itemId = item.getItemId();
                Intent intent = new Intent(ProfilActivity.this, MainActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);

                if (itemId == R.id.nav_materi) {
                     startActivity(intent);
                     finish();
                     return true;
                } else if (itemId == R.id.nav_habit) {
                    intent.putExtra("fragment", "habit");
                    startActivity(intent);
                    finish();
                    return true;
                } else if (itemId == R.id.nav_short) {
                    intent.putExtra("fragment", "short");
                    startActivity(intent);
                    finish();
                    return true;
                } else if (itemId == R.id.nav_tryout) {
                    intent.putExtra("fragment", "tryout");
                    startActivity(intent);
                    finish();
                    return true;
                } else if (itemId == R.id.nav_jadwal) {
                    intent.putExtra("fragment", "jadwal");
                    startActivity(intent);
                    finish();
                    return true;
                }
                return false;
            });
        }
    }

    private void openImagePicker() {
        Intent intent = new Intent();
        intent.setType("image/*");
        intent.setAction(Intent.ACTION_GET_CONTENT);
        imagePickerLauncher.launch(Intent.createChooser(intent, "Pilih Foto Profil"));
    }

    private void loadProfile() {
        progressDialog.setMessage("Memuat profil...");
        progressDialog.show();

        ApiService apiService = ApiConfig.getApiService();
        Call<ProfileResponse> call = apiService.getProfile(userId);

        call.enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                progressDialog.dismiss();
                if (response.isSuccessful() && response.body() != null) {
                    ProfileResponse.Data data = response.body().getData();
                    if (data != null) {
                        etNama.setText(data.getNama());
                        etEmail.setText(data.getEmail());
                        etNomorHp.setText(data.getTelepon());
                        tvStatusUser.setText(data.getTipeUser() != null ? data.getTipeUser() : "Free");

                        // Load Foto Profil
                        if (data.getFoto() != null && !data.getFoto().isEmpty()) {
                            String imageUrl = data.getFoto();
                            // Cek apakah URL absolut atau relatif
                            if (!imageUrl.startsWith("http")) {
                                // Buat base URL (menghapus 'api/' dari BASE_URL)
                                String baseUrl = ApiConfig.BASE_URL;
                                if (baseUrl.endsWith("api/")) {
                                    baseUrl = baseUrl.substring(0, baseUrl.length() - 4);
                                }
                                // Pastikan formatnya benar
                                if (!baseUrl.endsWith("/") && !imageUrl.startsWith("/")) {
                                    baseUrl += "/";
                                }
                                imageUrl = baseUrl + imageUrl;
                            }
                            
                            Log.d("ProfilActivity", "Loading Image: " + imageUrl);

                            Glide.with(ProfilActivity.this)
                                    .load(imageUrl)
                                    .placeholder(R.drawable.ic_user)
                                    .error(R.drawable.ic_user)
                                    .circleCrop()
                                    .diskCacheStrategy(DiskCacheStrategy.NONE) 
                                    .skipMemoryCache(true) 
                                    .into(ivProfilePhoto);
                        }
                    }
                } else {
                    Toast.makeText(ProfilActivity.this, "Gagal memuat profil: " + response.message(), Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<ProfileResponse> call, Throwable t) {
                progressDialog.dismiss();
                Toast.makeText(ProfilActivity.this, "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void updateProfile() {
        String nama = etNama.getText().toString().trim();
        String telepon = etNomorHp.getText().toString().trim();
        String passwordLama = etPasswordLama.getText().toString().trim();
        String passwordBaru = etPasswordBaru.getText().toString().trim();

        if (nama.isEmpty() || telepon.isEmpty()) {
            Toast.makeText(this, "Nama dan Nomor HP tidak boleh kosong", Toast.LENGTH_SHORT).show();
            return;
        }

        progressDialog.setMessage("Menyimpan perubahan...");
        progressDialog.show();
        btnSimpan.setEnabled(false);

        Log.d("ProfilActivity", "Updating user: " + userId + ", nama: " + nama);

        ApiService apiService = ApiConfig.getApiService();
        Call<ProfileResponse> call = apiService.updateProfile(userId, nama, telepon, passwordLama, passwordBaru);

        call.enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                progressDialog.dismiss();
                btnSimpan.setEnabled(true);

                if (response.isSuccessful() && response.body() != null) {
                    String status = response.body().getStatus();
                    String message = response.body().getMessage();
                    
                    Toast.makeText(ProfilActivity.this, message, Toast.LENGTH_LONG).show();
                    
                    if ("success".equals(status)) {
                        etPasswordLama.setText("");
                        etPasswordBaru.setText("");
                    }
                } else {
                    try {
                        String errorBody = response.errorBody() != null ? response.errorBody().string() : "{}";
                        Log.e("ProfilActivity", "Update Failed: " + response.code() + " - " + errorBody);

                        // Coba parse pesan dari response JSON jika tersedia
                        String serverMessage = null;
                        try {
                            org.json.JSONObject obj = new org.json.JSONObject(errorBody);
                            if (obj.has("message")) serverMessage = obj.optString("message");
                            else if (obj.has("error")) serverMessage = obj.optString("error");
                        } catch (Exception je) {
                            // bukan JSON, ignore
                        }

                        // Jika pesan berhubungan dengan password, tampilkan AlertDialog agar lebih jelas
                        if (serverMessage != null && serverMessage.toLowerCase().contains("password")) {
                            new androidx.appcompat.app.AlertDialog.Builder(ProfilActivity.this)
                                    .setTitle("Perhatian")
                                    .setMessage(serverMessage)
                                    .setPositiveButton("OK", null)
                                    .show();
                        } else if (serverMessage != null && !serverMessage.isEmpty()) {
                            // Jika ada pesan lain dari server, tampilkan Toast biasa
                            Toast.makeText(ProfilActivity.this, serverMessage, Toast.LENGTH_LONG).show();
                        } else {
                            Toast.makeText(ProfilActivity.this, "Gagal update profil: " + response.message(), Toast.LENGTH_SHORT).show();
                        }
                    } catch (IOException e) {
                        e.printStackTrace();
                        Toast.makeText(ProfilActivity.this, "Gagal update profil", Toast.LENGTH_SHORT).show();
                    }
                }
            }

            @Override
            public void onFailure(Call<ProfileResponse> call, Throwable t) {
                progressDialog.dismiss();
                btnSimpan.setEnabled(true);
                Log.e("ProfilActivity", "Connection Error: " + t.getMessage());
                Toast.makeText(ProfilActivity.this, "Koneksi Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void uploadPhoto() {
        if (selectedImageUri == null) return;

        File file = getFileFromUri(selectedImageUri);
        if (file == null) {
            Toast.makeText(this, "Gagal memproses gambar", Toast.LENGTH_SHORT).show();
            return;
        }

        progressDialog.setMessage("Mengupload foto...");
        progressDialog.show();

        RequestBody userIdPart = RequestBody.create(MediaType.parse("text/plain"), userId);
        
        // Menggunakan "foto" sebagai nama field sesuai dengan ApiService dan kemungkinan backend
        RequestBody requestFile = RequestBody.create(MediaType.parse("image/jpeg"), file);
        MultipartBody.Part body = MultipartBody.Part.createFormData("foto", file.getName(), requestFile);

        ApiService apiService = ApiConfig.getApiService();
        Call<ProfileResponse> call = apiService.updateProfilePhoto(userIdPart, body);

        call.enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(Call<ProfileResponse> call, Response<ProfileResponse> response) {
                progressDialog.dismiss();
                if (response.isSuccessful() && response.body() != null) {
                    String status = response.body().getStatus();
                    String message = response.body().getMessage();
                    Toast.makeText(ProfilActivity.this, message, Toast.LENGTH_SHORT).show();
                    
                    if ("success".equals(status)) {
                        // Reload profil untuk mendapatkan URL gambar yang baru dari server
                        loadProfile(); 
                    }
                } else {
                     try {
                        String errorBody = response.errorBody() != null ? response.errorBody().string() : "Unknown Error";
                        Log.e("ProfilActivity", "Upload Failed: " + response.code() + " - " + errorBody);
                        Toast.makeText(ProfilActivity.this, "Gagal upload: " + response.message(), Toast.LENGTH_SHORT).show();
                    } catch (IOException e) {
                        e.printStackTrace();
                    }
                }
            }

            @Override
            public void onFailure(Call<ProfileResponse> call, Throwable t) {
                progressDialog.dismiss();
                Toast.makeText(ProfilActivity.this, "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private File getFileFromUri(Uri uri) {
        try {
            String extension = ".jpg"; // Default
            String mime = getContentResolver().getType(uri);
            if (mime != null) {
                if (mime.contains("png")) extension = ".png";
                else if (mime.contains("jpeg") || mime.contains("jpg")) extension = ".jpg";
            }

            File tempFile = File.createTempFile("upload_foto", extension, getCacheDir());
            tempFile.deleteOnExit();
            
            java.io.InputStream inputStream = getContentResolver().openInputStream(uri);
            java.io.FileOutputStream out = new java.io.FileOutputStream(tempFile);
            byte[] buffer = new byte[1024];
            int read;
            while ((read = inputStream.read(buffer)) != -1) {
                out.write(buffer, 0, read);
            }
            inputStream.close();
            out.close();
            return tempFile;
        } catch (Exception e) {
            e.printStackTrace();
            return null;
        }
    }

    private void showLogoutConfirmation() {
        new AlertDialog.Builder(this)
                .setTitle("Konfirmasi Keluar")
                .setMessage("Apakah Anda yakin ingin keluar dari aplikasi?")
                .setPositiveButton("Ya", (dialog, which) -> logout())
                .setNegativeButton("Batal", null)
                .show();
    }

    private void logout() {
        SharedPreferences preferences = getSharedPreferences("user_session", MODE_PRIVATE);
        SharedPreferences.Editor editor = preferences.edit();
        editor.clear();
        editor.apply();

        Intent intent = new Intent(ProfilActivity.this, LoginActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
        startActivity(intent);
        finish();
    }
}
