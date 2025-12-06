package com.example.neoblue;

import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.text.method.PasswordTransformationMethod;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageButton;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.example.neoblue.api.ApiConfig;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class ResetPasswordActivity extends AppCompatActivity {

    private EditText etToken, etPassword, etPasswordConfirm;
    private Button btnSavePassword;
    private ProgressBar pbLoading;
    private TextView tvErrorMessage;
    private ImageButton btnTogglePassword, btnTogglePasswordConfirm;

    private boolean isPasswordVisible = false;
    private boolean isPasswordConfirmVisible = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_reset_password);

        // Initialize UI components
        etToken = findViewById(R.id.et_token_reset);
        etPassword = findViewById(R.id.et_password_reset);
        etPasswordConfirm = findViewById(R.id.et_password_confirm_reset);
        btnSavePassword = findViewById(R.id.btn_save_password);
        pbLoading = findViewById(R.id.pb_loading_reset);
        tvErrorMessage = findViewById(R.id.tv_error_message_reset);
        btnTogglePassword = findViewById(R.id.btn_toggle_password_reset);
        btnTogglePasswordConfirm = findViewById(R.id.btn_toggle_password_confirm_reset);

        // Optional: Get pre-filled token from intent if available (e.g., from deep link)
        // But now user will manually input it or we can autofill if passed
        String tokenExtra = getIntent().getStringExtra("TOKEN");
        if (tokenExtra != null) {
            etToken.setText(tokenExtra);
        }

        setupClickListeners();
    }

    private void setupClickListeners() {
        btnSavePassword.setOnClickListener(v -> {
            String token = etToken.getText().toString().trim();
            String password = etPassword.getText().toString().trim();
            String passwordConfirm = etPasswordConfirm.getText().toString().trim();
            if (validateInput(token, password, passwordConfirm)) {
                performPasswordReset(token, password, passwordConfirm);
            }
        });

        btnTogglePassword.setOnClickListener(v -> togglePasswordVisibility());
        btnTogglePasswordConfirm.setOnClickListener(v -> togglePasswordConfirmVisibility());
    }

    private boolean validateInput(String token, String password, String passwordConfirm) {
        if (token.isEmpty()) {
            showError("Kode OTP tidak boleh kosong.");
            return false;
        }
        if (password.length() < 8) {
            showError("Password minimal 8 karakter.");
            return false;
        }
        if (!password.equals(passwordConfirm)) {
            showError("Konfirmasi password tidak cocok.");
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

    private void togglePasswordConfirmVisibility() {
        if (isPasswordConfirmVisible) {
            etPasswordConfirm.setTransformationMethod(new PasswordTransformationMethod());
            btnTogglePasswordConfirm.setImageResource(R.drawable.ic_eye);
        } else {
            etPasswordConfirm.setTransformationMethod(null);
            btnTogglePasswordConfirm.setImageResource(R.drawable.ic_eye_slash);
        }
        isPasswordConfirmVisible = !isPasswordConfirmVisible;
        etPasswordConfirm.setSelection(etPasswordConfirm.length());
    }

    private void showLoading(boolean isLoading) {
        if (isLoading) {
            pbLoading.setVisibility(View.VISIBLE);
            btnSavePassword.setText("");
            btnSavePassword.setEnabled(false);
        } else {
            pbLoading.setVisibility(View.GONE);
            btnSavePassword.setText("Simpan Password");
            btnSavePassword.setEnabled(true);
        }
    }

    private void showError(String message) {
        tvErrorMessage.setText(message);
        tvErrorMessage.setVisibility(View.VISIBLE);
    }

    private void hideError() {
        tvErrorMessage.setVisibility(View.GONE);
    }

    private void performPasswordReset(String token, String password, String passwordConfirm) {
        hideError();
        showLoading(true);

        ExecutorService executor = Executors.newSingleThreadExecutor();
        Handler handler = new Handler(Looper.getMainLooper());

        executor.execute(() -> {
            String apiUrl = ApiConfig.BASE_URL + "api_reset_password.php";

            String result = "";
            try {
                URL url = new URL(apiUrl);
                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/json; charset=UTF-8");
                conn.setDoOutput(true);
                conn.setConnectTimeout(ApiConfig.TIMEOUT);
                conn.setReadTimeout(ApiConfig.TIMEOUT);

                JSONObject jsonParam = new JSONObject();
                jsonParam.put("token", token);
                jsonParam.put("password", password);
                jsonParam.put("password_confirm", passwordConfirm);

                OutputStream os = conn.getOutputStream();
                os.write(jsonParam.toString().getBytes("UTF-8"));
                os.close();

                int responseCode = conn.getResponseCode();
                if (responseCode == HttpURLConnection.HTTP_OK) {
                    BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
                    StringBuilder response = new StringBuilder();
                    String inputLine;
                    while ((inputLine = in.readLine()) != null) {
                        response.append(inputLine);
                    }
                    in.close();
                    result = response.toString();
                } else {
                     BufferedReader in = new BufferedReader(new InputStreamReader(conn.getErrorStream()));
                    StringBuilder errorResponse = new StringBuilder();
                    String line;
                    while ((line = in.readLine()) != null) {
                        errorResponse.append(line);
                    }
                    in.close();
                    
                    // Check if response starts with HTML tag, which means server error/config issue
                    String errStr = errorResponse.toString();
                    if (errStr.trim().startsWith("<")) {
                         result = "{\"status\":\"error\", \"message\":\"Server Error " + responseCode + ": Konfigurasi server bermasalah.\"}";
                    } else {
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
                    String status = jsonObject.optString("status", "error");

                    if ("success".equals(status)) {
                        showCustomSuccessDialog("Password Berhasil", "Password berhasil diubah. Silakan login.", () -> {
                            Intent intent = new Intent(ResetPasswordActivity.this, LoginActivity.class);
                            intent.setFlags(Intent.FLAG_ACTIVITY_NEW_TASK | Intent.FLAG_ACTIVITY_CLEAR_TASK);
                            startActivity(intent);
                            finish();
                        });
                    } else {
                        String message = jsonObject.optString("message", "Gagal mereset password.");
                        showError(message);
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    // Tampilkan potongan response asli untuk debugging
                    String debugMsg = finalResult;
                    if (debugMsg.length() > 150) {
                        debugMsg = debugMsg.substring(0, 150) + "...";
                    }
                    showError("Server Response Error: " + debugMsg);
                }
            });
        });
    }

    private void showCustomSuccessDialog(String title, String message, Runnable onDismissAction) {
        androidx.appcompat.app.AlertDialog.Builder builder = new androidx.appcompat.app.AlertDialog.Builder(this);
        android.view.LayoutInflater inflater = this.getLayoutInflater();
        android.view.View dialogView = inflater.inflate(R.layout.dialog_success, null);
        builder.setView(dialogView);

        androidx.appcompat.app.AlertDialog dialog = builder.create();
        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new android.graphics.drawable.ColorDrawable(android.graphics.Color.TRANSPARENT));
        }

        android.widget.TextView tvTitle = dialogView.findViewById(R.id.dialog_title);
        android.widget.TextView tvMessage = dialogView.findViewById(R.id.dialog_message);
        com.google.android.material.button.MaterialButton btnOk = dialogView.findViewById(R.id.dialog_button);

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
}