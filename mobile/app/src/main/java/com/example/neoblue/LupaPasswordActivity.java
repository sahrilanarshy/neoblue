package com.example.neoblue;

import android.content.Intent;
import android.graphics.drawable.ColorDrawable;
import android.os.Bundle;
import android.os.Handler;
import android.os.Looper;
import android.util.Patterns;
import android.view.LayoutInflater;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
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

public class LupaPasswordActivity extends AppCompatActivity {

    private EditText etEmail;
    private Button btnReset;
    private ProgressBar pbLoading;
    private TextView tvErrorMessage, tvKembaliLogin;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_lupa_password);

        etEmail = findViewById(R.id.et_email_lupa);
        btnReset = findViewById(R.id.btn_reset_password);
        pbLoading = findViewById(R.id.pb_loading_lupa);
        tvErrorMessage = findViewById(R.id.tv_error_message_lupa);
        tvKembaliLogin = findViewById(R.id.tv_kembali_login);

        btnReset.setOnClickListener(v -> {
            String email = etEmail.getText().toString().trim();
            if (validateInput(email)) {
                sendResetLink(email);
            }
        });

        tvKembaliLogin.setOnClickListener(v -> finish());
    }

    private boolean validateInput(String email) {
        if (email.isEmpty() || !Patterns.EMAIL_ADDRESS.matcher(email).matches()) {
            showError("Alamat email tidak valid.");
            return false;
        }
        return true;
    }

    private void showLoading(boolean isLoading) {
        if (isLoading) {
            pbLoading.setVisibility(View.VISIBLE);
            btnReset.setText("");
            btnReset.setEnabled(false);
        } else {
            pbLoading.setVisibility(View.GONE);
            btnReset.setText("Kirim Kode OTP");
            btnReset.setEnabled(true);
        }
    }

    private void showError(String message) {
        tvErrorMessage.setText(message);
        tvErrorMessage.setTextColor(getResources().getColor(R.color.colorErrorText));
        tvErrorMessage.setBackgroundResource(R.drawable.bg_error_alert);
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
            // Setelah sukses request OTP, arahkan ke halaman Reset Password untuk input token
            Intent intent = new Intent(LupaPasswordActivity.this, ResetPasswordActivity.class);
            startActivity(intent);
            finish();
        });

        dialog.setCancelable(false);
        dialog.show();
    }

    private void sendResetLink(String email) {
        hideError();
        showLoading(true);

        ExecutorService executor = Executors.newSingleThreadExecutor();
        Handler handler = new Handler(Looper.getMainLooper());

        executor.execute(() -> {
            // Mengembalikan endpoint ke api_forgot_password.php karena api_lupa_password.php mungkin belum dibuat di server
            String apiUrl = ApiConfig.BASE_URL + "api_forgot_password.php";

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
                jsonParam.put("email", email);

                OutputStream os = conn.getOutputStream();
                os.write(jsonParam.toString().getBytes("UTF-8"));
                os.close();

                int responseCode = conn.getResponseCode();
                if (responseCode == HttpURLConnection.HTTP_OK) {
                    BufferedReader in = new BufferedReader(new InputStreamReader(conn.getInputStream()));
                    StringBuilder response = new StringBuilder();
                    String line;
                    while ((line = in.readLine()) != null) {
                        response.append(line);
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
                    
                    String errStr = errorResponse.toString();
                    if (errStr.trim().startsWith("<")) {
                         result = "{\"status\":\"error\", \"message\":\"Server Error " + responseCode + ": Konfigurasi server bermasalah (File mungkin tidak ditemukan).\"}";
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
                        String message = jsonObject.optString("message", "Kode OTP telah dikirim ke email Anda.");
                        showCustomSuccessDialog("Email Terkirim", message);
                    } else {
                        String message = jsonObject.optString("message", "Terjadi kesalahan.");
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
}