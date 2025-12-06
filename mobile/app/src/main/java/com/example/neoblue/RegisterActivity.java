package com.example.neoblue;

import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.text.method.PasswordTransformationMethod;
import android.util.Patterns;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AlertDialog;
import androidx.appcompat.app.AppCompatActivity;

import com.example.neoblue.api.ApiConfig;
import com.google.android.material.button.MaterialButton;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class RegisterActivity extends AppCompatActivity {

    private EditText etNama, etEmail, etTelepon, etPassword;
    private Button btnRegister;
    private ProgressBar pbLoading;
    private TextView tvErrorMessage;
    private ImageButton btnTogglePassword;
    private boolean isPasswordVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);

        // Initialize UI components
        etNama = findViewById(R.id.et_nama_register);
        etEmail = findViewById(R.id.et_email_register);
        etTelepon = findViewById(R.id.et_telepon_register);
        etPassword = findViewById(R.id.et_password_register);
        btnRegister = findViewById(R.id.btn_register);
        pbLoading = findViewById(R.id.pb_loading_register);
        tvErrorMessage = findViewById(R.id.tv_error_message_register);
        btnTogglePassword = findViewById(R.id.btn_toggle_password_register);

        // Setup Listeners
        setupClickListeners();
    }

    private void setupClickListeners() {
        btnRegister.setOnClickListener(v -> {
            String nama = etNama.getText().toString().trim();
            String email = etEmail.getText().toString().trim();
            String telepon = etTelepon.getText().toString().trim();
            String password = etPassword.getText().toString().trim();

            if (validateInput(nama, email, telepon, password)) {
                performRegistration(nama, email, telepon, password);
            }
        });

        TextView tvLogin = findViewById(R.id.tv_login_sekarang);
        tvLogin.setOnClickListener(v -> finish()); // Go back to LoginActivity

        btnTogglePassword.setOnClickListener(v -> togglePasswordVisibility());
    }

    private boolean validateInput(String nama, String email, String telepon, String password) {
        if (nama.isEmpty()) {
            showError("Nama lengkap tidak boleh kosong.");
            return false;
        }
        if (email.isEmpty() || !Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            showError("Masukkan alamat email yang valid.");
            return false;
        }
        if (telepon.isEmpty() || !Patterns.PHONE.matcher(telepon).matches()) {
            showError("Masukkan nomor telepon yang valid.");
            return false;
        }
        if (password.length() < 8) {
            showError("Password minimal harus 8 karakter.");
            return false;
        }
        return true;
    }

    private void togglePasswordVisibility() {
        if (isPasswordVisible) {
            etPassword.setTransformationMethod(new PasswordTransformationMethod());
            btnTogglePassword.setImageResource(R.drawable.ic_eye);
        } else {
            etPassword.setTransformationMethod(null);
            btnTogglePassword.setImageResource(R.drawable.ic_eye_slash);
        }
        isPasswordVisible = !isPasswordVisible;
        etPassword.setSelection(etPassword.length());
    }

    private void showLoading(boolean isLoading) {
        if (isLoading) {
            pbLoading.setVisibility(View.VISIBLE);
            btnRegister.setText("");
            btnRegister.setEnabled(false);
        } else {
            pbLoading.setVisibility(View.GONE);
            btnRegister.setText("Daftar Sekarang");
            btnRegister.setEnabled(true);
        }
    }

    private void showError(String message) {
        // Also show custom dialog for error
        showCustomErrorDialog("Registrasi Gagal", message);
        tvErrorMessage.setText(message);
        tvErrorMessage.setVisibility(View.VISIBLE);
    }

    private void hideError() {
        tvErrorMessage.setVisibility(View.GONE);
    }

    private void showCustomSuccessDialog(String title, String message) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        LayoutInflater inflater = this.getLayoutInflater();
        View dialogView = inflater.inflate(R.layout.dialog_success, null);
        builder.setView(dialogView);

        AlertDialog dialog = builder.create();
        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        }

        TextView tvTitle = dialogView.findViewById(R.id.dialog_title);
        TextView tvMessage = dialogView.findViewById(R.id.dialog_message);
        MaterialButton btnOk = dialogView.findViewById(R.id.dialog_button);

        tvTitle.setText(title);
        tvMessage.setText(message);

        btnOk.setOnClickListener(v -> {
            dialog.dismiss();
            // Redirect to login
            Intent intent = new Intent(RegisterActivity.this, LoginActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
            intent.putExtra("REGISTRATION_SUCCESS", true);
            startActivity(intent);
            finish();
        });

        dialog.setCancelable(false);
        dialog.show();
    }

    private void showCustomErrorDialog(String title, String message) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        LayoutInflater inflater = this.getLayoutInflater();
        View dialogView = inflater.inflate(R.layout.dialog_error, null);
        builder.setView(dialogView);

        AlertDialog dialog = builder.create();
        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new ColorDrawable(android.graphics.Color.TRANSPARENT));
        }

        TextView tvTitle = dialogView.findViewById(R.id.dialog_title);
        TextView tvMessage = dialogView.findViewById(R.id.dialog_message);
        MaterialButton btnTryAgain = dialogView.findViewById(R.id.dialog_button);

        tvTitle.setText(title);
        tvMessage.setText(message);

        btnTryAgain.setOnClickListener(v -> dialog.dismiss());

        dialog.show();
    }

    private void performRegistration(String nama, String email, String telepon, String password) {
        hideError();
        showLoading(true);

        ExecutorService executor = Executors.newSingleThreadExecutor();
        Handler handler = new Handler(Looper.getMainLooper());

        executor.execute(() -> {
            String apiUrl = ApiConfig.BASE_URL + "api_register.php";

            String result = "";
            try {
                URL url = new URL(apiUrl);
                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/json; charset=UTF-8");
                conn.setRequestProperty("User-Agent", "Mozilla/5.0"); // Tambahkan User-Agent
                conn.setDoOutput(true);
                conn.setConnectTimeout(ApiConfig.TIMEOUT);
                conn.setReadTimeout(ApiConfig.TIMEOUT);

                JSONObject jsonParam = new JSONObject();
                jsonParam.put("nama", nama);
                jsonParam.put("email", email);
                jsonParam.put("telepon", telepon);
                jsonParam.put("password", password);

                OutputStream os = conn.getOutputStream();
                os.write(jsonParam.toString().getBytes("UTF-8"));
                os.close();

                int responseCode = conn.getResponseCode();
                if (responseCode == HttpURLConnection.HTTP_OK || responseCode == HttpURLConnection.HTTP_CREATED) { // Handle 201 Created
                    BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
                    StringBuilder response = new StringBuilder();
                    String line;
                    while ((line = in.readLine()) != null) {
                        response.append(line);
                    }
                    in.close();
                    result = response.toString();
                } else if (responseCode == HttpURLConnection.HTTP_CONFLICT) {
                    // 409 Conflict - Email already exists
                    result = "{\"status\":\"error\", \"message\":\"Email sudah terdaftar. Silakan gunakan email lain atau login.\"}";
                } else {
                    // Try to read error detail
                    try {
                         BufferedReader in = new BufferedReader(new InputStreamReader(conn.getErrorStream()));
                         StringBuilder response = new StringBuilder();
                         String line;
                         while ((line = in.readLine()) != null) {
                             response.append(line);
                         }
                         in.close();
                         
                         // Check for HTML response (e.g. 403 Forbidden HTML page)
                         String errorStr = response.toString().trim();
                         if (errorStr.startsWith("<")) {
                             result = "{\"status\":\"error\", \"message\":\"Server Error " + responseCode + ": Konfigurasi server bermasalah.\"}";
                         } else {
                             // Check if response is JSON
                             new JSONObject(errorStr); // Test parsing
                             result = errorStr;
                         }
                    } catch (Exception e) {
                         result = "{\"status\":\"error\", \"message\":\"Server error: " + responseCode + "\"}";
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
                result = "{\"status\":\"error\", \"message\":\"Connection failed: " + e.getMessage() + "\"}";
            }

            String finalResult = result;
            handler.post(() -> {
                showLoading(false);
                try {
                    JSONObject jsonObject = new JSONObject(finalResult);
                    // Handle response without "status" field if necessary, or ensure backend sends it
                    if (jsonObject.has("status")) {
                        String status = jsonObject.getString("status");
                        if ("success".equals(status)) {
                            showCustomSuccessDialog("Registrasi Berhasil", "Akun Anda berhasil dibuat! Silakan login untuk melanjutkan.");
                        } else {
                            String message = jsonObject.optString("message", "Terjadi kesalahan.");
                            showError(message);
                        }
                    } else {
                         // Fallback if status field missing but request was 200/201
                         // Assuming successful if we got here with valid JSON
                         showCustomSuccessDialog("Registrasi Berhasil", "Akun Anda berhasil dibuat! Silakan login untuk melanjutkan.");
                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                    showError("Terjadi kesalahan server: " + finalResult);
                }
            });
        });
    }
}