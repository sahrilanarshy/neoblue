package com.example.neoblue;

import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.text.Html;
import android.text.method.PasswordTransformationMethod;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ProgressBar;
import android.widget.TextView;

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

public class LoginActivity extends AppCompatActivity {

    private EditText etEmail, etPassword;
    private Button btnLogin;
    private ProgressBar pbLoading;
    private TextView tvErrorMessage;
    private ImageButton btnTogglePassword;
    private boolean isPasswordVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);

        // Initialize UI components
        etEmail = findViewById(R.id.et_email_login);
        etPassword = findViewById(R.id.et_password_login);
        btnLogin = findViewById(R.id.btn_login);
        pbLoading = findViewById(R.id.pb_loading);
        tvErrorMessage = findViewById(R.id.tv_error_message);
        btnTogglePassword = findViewById(R.id.btn_toggle_password);

        // Check for registration success message
        if (getIntent().getBooleanExtra("REGISTRATION_SUCCESS", false)) {
            TextView tvSuccessMessage = findViewById(R.id.tv_success_message);
            tvSuccessMessage.setText("Registrasi berhasil! Silakan login.");
            tvSuccessMessage.setVisibility(View.VISIBLE);
        }

        // Setup Listeners
        setupClickListeners();
    }

    private void setupClickListeners() {
        // Login button listener
        btnLogin.setOnClickListener(v -> {
            String email = etEmail.getText().toString().trim();
            String password = etPassword.getText().toString().trim();
            if (validateInput(email, password)) {
                performLogin(email, password);
            }
        });

        // "Daftar Sekarang" listener
        TextView tvDaftar = findViewById(R.id.tv_daftar_sekarang);
        tvDaftar.setOnClickListener(v -> {
            Intent intent = new Intent(LoginActivity.this, RegisterActivity.class);
            startActivity(intent);
        });

        // "Lupa Password" listener
        TextView tvLupaPass = findViewById(R.id.tv_lupa_password);
        tvLupaPass.setOnClickListener(v -> {
            Intent intent = new Intent(LoginActivity.this, LupaPasswordActivity.class);
            startActivity(intent);
        });

        // Password visibility toggle listener
        btnTogglePassword.setOnClickListener(v -> togglePasswordVisibility());
    }

    private boolean validateInput(String email, String password) {
        if (email.isEmpty() || !android.util.Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            showError("Masukkan alamat email yang valid.");
            return false;
        }
        if (password.isEmpty()) {
            showError("Password tidak boleh kosong.");
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
            btnLogin.setText("");
            btnLogin.setEnabled(false);
        } else {
            pbLoading.setVisibility(View.GONE);
            btnLogin.setText("Masuk Sekarang");
            btnLogin.setEnabled(true);
        }
    }

    private void showError(String message) {
        showCustomErrorDialog("Login Gagal", message);
        tvErrorMessage.setText(message);
        tvErrorMessage.setVisibility(View.VISIBLE);
    }

    private void hideError() {
        tvErrorMessage.setVisibility(View.GONE);
    }

    private void showCustomSuccessDialog(String title, String message, Runnable onDismissAction) {
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
            if (onDismissAction != null) {
                onDismissAction.run();
            }
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

    private void performLogin(String email, String password) {
        hideError();
        showLoading(true);

        ExecutorService executor = Executors.newSingleThreadExecutor();
        Handler handler = new Handler(Looper.getMainLooper());

        executor.execute(() -> {
            String apiUrl = ApiConfig.BASE_URL + "api_mobile_login.php";

            String result = "";
            try {
                URL url = new URL(apiUrl);
                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/json; charset=UTF-8");
                // Gunakan User-Agent lengkap agar tidak diblokir (403)
                conn.setRequestProperty("User-Agent", "Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36");
                conn.setRequestProperty("Accept", "application/json");
                conn.setDoOutput(true);
                conn.setDoInput(true);
                conn.setConnectTimeout(ApiConfig.TIMEOUT);
                conn.setReadTimeout(ApiConfig.TIMEOUT);

                JSONObject jsonParam = new JSONObject();
                jsonParam.put("email", email);
                jsonParam.put("password", password);

                OutputStream os = conn.getOutputStream();
                os.write(jsonParam.toString().getBytes("UTF-8"));
                os.close();

                int responseCode = conn.getResponseCode();

                if (responseCode == HttpURLConnection.HTTP_OK) {
                    BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
                    String inputLine;
                    StringBuilder response = new StringBuilder();
                    while ((inputLine = in.readLine()) != null) {
                        response.append(inputLine);
                    }
                    in.close();
                    result = response.toString();
                } else {
                    String errorMsg = "";
                    try {
                        BufferedReader in = new BufferedReader(new InputStreamReader(conn.getErrorStream()));
                        String inputLine;
                        StringBuilder errorResponse = new StringBuilder();
                        while ((inputLine = in.readLine()) != null) {
                            errorResponse.append(inputLine);
                        }
                        in.close();
                        errorMsg = errorResponse.toString();
                    } catch (Exception ex) {
                        errorMsg = "";
                    }

                    // Jika server mengembalikan halaman HTML, tampilkan pesan server generik
                    if (errorMsg.trim().startsWith("<")) {
                        String title = "";
                        if (errorMsg.contains("<title>")) {
                            int start = errorMsg.indexOf("<title>") + 7;
                            int end = errorMsg.indexOf("</title>");
                            if (end > start) title = errorMsg.substring(start, end);
                        }
                        try {
                            JSONObject obj = new JSONObject();
                            obj.put("status", "error");
                            obj.put("message", "Server Error " + responseCode + ": " + title + " (Cek Izin Server/Firewall)");
                            result = obj.toString();
                        } catch (Exception j) {
                            result = "{\"status\":\"error\",\"message\":\"Server Error " + responseCode + "\"}";
                        }
                    } else {
                        // Jika error body berformat JSON, gunakan pesan dari server
                        try {
                            JSONObject errJson = new JSONObject(errorMsg);
                            String serverMsg = errJson.optString("message", errJson.optString("error", errorMsg));
                            JSONObject obj = new JSONObject();
                            obj.put("status", "error");
                            obj.put("message", serverMsg);
                            result = obj.toString();
                        } catch (Exception parseEx) {
                            // Jika tidak JSON, tapi response code mengindikasikan credential problem, perlihatkan pesan yang sesuai
                            if (responseCode == HttpURLConnection.HTTP_UNAUTHORIZED || responseCode == HttpURLConnection.HTTP_FORBIDDEN) {
                                try {
                                    JSONObject obj = new JSONObject();
                                    obj.put("status", "error");
                                    obj.put("message", "Email atau password salah.");
                                    result = obj.toString();
                                } catch (Exception j) {
                                    result = "{\"status\":\"error\",\"message\":\"Email atau password salah.\"}";
                                }
                            } else {
                                try {
                                    JSONObject obj = new JSONObject();
                                    obj.put("status", "error");
                                    obj.put("message", errorMsg.isEmpty() ? ("Server error: " + responseCode) : errorMsg);
                                    result = obj.toString();
                                } catch (Exception j) {
                                    result = "{\"status\":\"error\",\"message\":\"Server error: " + responseCode + "\"}";
                                }
                            }
                        }
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
                    Log.d("LoginActivity", "Response: " + finalResult);
                    
                    if (finalResult == null || finalResult.isEmpty()) {
                        showError("Empty response from server.");
                        return;
                    }

                    JSONObject jsonObject = new JSONObject(finalResult);
                    String status = jsonObject.getString("status");

                    if ("success".equals(status)) {
                        JSONObject data = jsonObject.getJSONObject("data");
                        
                        String userId = data.optString("user_id");
                        if (userId.isEmpty()) {
                            userId = data.optString("id");
                        }
                        
                        String role = data.optString("role", "user"); 
                        String tipeUser = data.optString("tipe_user", "Free");

                        SharedPreferences preferences = getSharedPreferences("user_session", MODE_PRIVATE);
                        SharedPreferences.Editor editor = preferences.edit();
                        editor.putString("user_id", userId);
                        editor.putString("user_role", role);
                        editor.putString("tipe_user", tipeUser);
                        editor.putBoolean("is_logged_in", true);
                        editor.apply();

                        showCustomSuccessDialog("Login Berhasil", "Selamat datang kembali!", () -> {
                            Intent intent = new Intent(LoginActivity.this, MainActivity.class);
                            startActivity(intent);
                            finish(); 
                        });

                    } else {
                            String message = jsonObject.optString("message", "Terjadi kesalahan.");
                            message = mapServerMessage(message, "Terjadi kesalahan.");
                            showError(message);
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    String debugMsg = finalResult.length() > 100 ? finalResult.substring(0, 100) + "..." : finalResult;
                    showError("Invalid server response: " + debugMsg);
                }
            });
        });
    }

    private String mapServerMessage(String msg, String defaultMsg) {
        if (msg == null) return defaultMsg;
        String m = msg.toLowerCase();
        if (m.contains("invalid") || m.contains("credential") || m.contains("password") || m.contains("email") || m.contains("salah") || m.contains("wrong")) {
            return "Email atau password salah.";
        }
        if (m.contains("blocked") || m.contains("forbidden") || m.contains("403") || m.contains("banned")) {
            return "Akses ditolak oleh server. Cek izin atau coba lagi nanti.";
        }
        if (m.contains("not found") || m.contains("not exist") || m.contains("tidak ditemukan") || m.contains("not registered")) {
            return "Akun tidak ditemukan. Silakan registrasi terlebih dahulu.";
        }
        return msg;
    }
}
