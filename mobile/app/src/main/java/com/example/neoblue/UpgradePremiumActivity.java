package com.example.neoblue;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.database.Cursor;
import android.graphics.Color;
import android.graphics.drawable.ColorDrawable;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.Window;
import android.view.WindowManager;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.activity.result.ActivityResultLauncher;
import androidx.activity.result.contract.ActivityResultContracts;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.WindowCompat;

import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.PremiumDataResponse;
import com.example.neoblue.models.UpgradePremiumResponse;
import okhttp3.ResponseBody;
import org.json.JSONException;
import org.json.JSONObject;
import java.io.IOException;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import java.io.File;
import java.text.NumberFormat;
import java.util.ArrayList;
import java.util.List;
import java.util.Locale;

import okhttp3.MediaType;
import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class UpgradePremiumActivity extends AppCompatActivity {

    private static final int PICK_IMAGE_REQUEST = 1;
    private Uri selectedImageUri;
    private TextView tvSelectedFile;
    private TextView tvPaketNama, tvPaketHarga, tvPaketDetailNama, tvPaketDetailHarga, tvTotalHarga;
    private LinearLayout containerFitur; 
    private String currentPaketHarga = "0";
    
    private List<PremiumDataResponse.Metode> metodeList = new ArrayList<>();
    private PremiumDataResponse.Paket paketPremium = null;

    private final ActivityResultLauncher<Intent> imagePickerLauncher = registerForActivityResult(
            new ActivityResultContracts.StartActivityForResult(),
            result -> {
                if (result.getResultCode() == Activity.RESULT_OK && result.getData() != null) {
                    selectedImageUri = result.getData().getData();
                    if (tvSelectedFile != null) {
                        String fileName = getFileName(selectedImageUri);
                        tvSelectedFile.setText(fileName);
                        tvSelectedFile.setVisibility(View.VISIBLE);
                    }
                }
            }
    );

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        WindowCompat.setDecorFitsSystemWindows(getWindow(), false);
        setContentView(R.layout.activity_upgrade_premium);
        
        tvPaketNama = findViewById(R.id.tv_paket_nama);
        tvPaketHarga = findViewById(R.id.tv_paket_harga);
        tvPaketDetailNama = findViewById(R.id.tv_detail_paket_nama);
        tvPaketDetailHarga = findViewById(R.id.tv_detail_paket_harga);
        tvTotalHarga = findViewById(R.id.tv_total_harga);
        containerFitur = findViewById(R.id.container_fitur); 

        setupHeader();
        
        SharedPreferences preferences = getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String userRole = preferences.getString("user_role", "user"); 
        
        loadPremiumData();

        Button btnLangganan = findViewById(R.id.btn_langganan);
        if (btnLangganan != null) {
            btnLangganan.setOnClickListener(v -> {
                if (!"siswa".equalsIgnoreCase(userRole)) {
                     Toast.makeText(UpgradePremiumActivity.this, "Fitur ini hanya untuk Siswa.", Toast.LENGTH_SHORT).show();
                     return;
                }
                showPaymentDialog();
            });
        }

        setupBottomNavigation();
    }

    private void setupHeader() {
        ImageView btnNotif = findViewById(R.id.btn_notifikasi);
        if (btnNotif != null) {
            btnNotif.setOnClickListener(v -> startActivity(new Intent(UpgradePremiumActivity.this, NotifikasiActivity.class)));
        }

        ImageView btnProfil = findViewById(R.id.btn_profil);
        if (btnProfil != null) {
            btnProfil.setOnClickListener(v -> startActivity(new Intent(UpgradePremiumActivity.this, ProfilActivity.class)));
        }
        // header view is present in layout; keep behavior unchanged (no dynamic inset applied)
    }

    private void setupBottomNavigation() {
        BottomNavigationView bottomNavigationView = findViewById(R.id.bottom_navigation);
        if (bottomNavigationView != null) {
            bottomNavigationView.setOnItemSelectedListener(item -> {
                Intent intent = new Intent(UpgradePremiumActivity.this, MainActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);

                if (item.getItemId() == R.id.nav_materi) {
                   startActivity(intent);
                   return true;
                } else if (item.getItemId() == R.id.nav_habit) {
                    intent.putExtra("fragment", "habit");
                    startActivity(intent);
                    return true;
                } else if (item.getItemId() == R.id.nav_short) {
                     intent.putExtra("fragment", "short");
                     startActivity(intent);
                    return true;
                } else if (item.getItemId() == R.id.nav_tryout) {
                     intent.putExtra("fragment", "tryout");
                     startActivity(intent);
                    return true;
                } else if (item.getItemId() == R.id.nav_jadwal) {
                    intent.putExtra("fragment", "jadwal");
                    startActivity(intent);
                    return true;
                }
                return false;
            });
        }
    }

    private void loadPremiumData() {
        ApiService apiService = ApiConfig.getApiService();
        Call<PremiumDataResponse> call = apiService.getPremiumData();
        
        call.enqueue(new Callback<PremiumDataResponse>() {
            @Override
            public void onResponse(Call<PremiumDataResponse> call, Response<PremiumDataResponse> response) {
                if (response.isSuccessful() && response.body() != null && "success".equals(response.body().getStatus())) {
                    PremiumDataResponse.Data data = response.body().getData();
                    
                    if (data.getMetodePembayaran() != null) {
                        metodeList = data.getMetodePembayaran();
                    }

                    if (data.getPaket() != null && !data.getPaket().isEmpty()) {
                        PremiumDataResponse.Paket selectedPaket = null;
                        
                        for (PremiumDataResponse.Paket p : data.getPaket()) {
                            double harga = parseHarga(p.getHarga());
                            if (harga > 0) {
                                if (selectedPaket == null) {
                                    selectedPaket = p;
                                }
                                if ("1".equals(p.getIsUnggulan())) {
                                    selectedPaket = p;
                                    break;
                                }
                            }
                        }
                        
                        if (selectedPaket == null) {
                            selectedPaket = data.getPaket().get(0);
                        }
                        
                        paketPremium = selectedPaket;
                        updateUI(paketPremium);
                    }
                } else {
                    Toast.makeText(UpgradePremiumActivity.this, "Gagal memuat data premium.", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<PremiumDataResponse> call, Throwable t) {
                Toast.makeText(UpgradePremiumActivity.this, "Koneksi gagal: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
    
    private double parseHarga(String hargaStr) {
        try {
            if (hargaStr == null) return 0;
            if (hargaStr.contains("Rp")) {
                String clean = hargaStr.replace("Rp", "").trim();
                clean = clean.replace(".", ""); 
                clean = clean.replace(",", "."); 
                return Double.parseDouble(clean);
            } else {
                return Double.parseDouble(hargaStr.trim());
            }
        } catch (Exception e) {
            return 0;
        }
    }

    private void updateUI(PremiumDataResponse.Paket paket) {
        if (paket == null) return;

        try {
            String formattedPrice = formatRupiah(parseHarga(paket.getHarga()));
            currentPaketHarga = formattedPrice;

            if (tvPaketNama != null) tvPaketNama.setText(paket.getNamaPaket());
            if (tvPaketHarga != null) tvPaketHarga.setText(formattedPrice);
            
            if (tvPaketDetailNama != null) tvPaketDetailNama.setText(paket.getNamaPaket());
            if (tvPaketDetailHarga != null) tvPaketDetailHarga.setText(formattedPrice);
            if (tvTotalHarga != null) tvTotalHarga.setText(formattedPrice);
            
            if (containerFitur != null && paket.getFitur() != null) {
                containerFitur.removeAllViews(); 
                for (PremiumDataResponse.Fitur f : paket.getFitur()) {
                    addFiturView(f.getNamaFitur());
                }
            }
        } catch (NumberFormatException e) {
            e.printStackTrace();
        }
    }
    
    private void addFiturView(String namaFitur) {
        LinearLayout itemLayout = new LinearLayout(this);
        itemLayout.setOrientation(LinearLayout.HORIZONTAL);
        itemLayout.setLayoutParams(new LinearLayout.LayoutParams(
                LinearLayout.LayoutParams.MATCH_PARENT, 
                LinearLayout.LayoutParams.WRAP_CONTENT));
        itemLayout.setPadding(0, 0, 0, 24); 

        ImageView checkIcon = new ImageView(this);
        // Use custom premium check drawable for a nicer rounded green box with white check
        checkIcon.setImageResource(R.drawable.ic_premium_check_checked);
        LinearLayout.LayoutParams iconParams = new LinearLayout.LayoutParams(
            (int) (getResources().getDisplayMetrics().density * 28),
            (int) (getResources().getDisplayMetrics().density * 28));
        iconParams.setMargins(0, 0, (int) (getResources().getDisplayMetrics().density * 12), 0);
        checkIcon.setLayoutParams(iconParams);

        TextView tvFitur = new TextView(this);
        tvFitur.setText(namaFitur);
        tvFitur.setTextColor(getResources().getColor(R.color.black));
        tvFitur.setTextSize(14);
        
        itemLayout.addView(checkIcon);
        itemLayout.addView(tvFitur);
        
        containerFitur.addView(itemLayout);
    }
    
    private String formatRupiah(Double number){
        Locale localeID = new Locale("in", "ID");
        NumberFormat formatRupiah = NumberFormat.getCurrencyInstance(localeID);
        return formatRupiah.format(number).replace("Rp", "Rp ");
    }

    private void showPaymentDialog() {
        final Dialog dialog = new Dialog(this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.dialog_payment);
        
        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
            dialog.getWindow().setLayout(
                WindowManager.LayoutParams.MATCH_PARENT,
                WindowManager.LayoutParams.WRAP_CONTENT
            );
        }
        
        TextView tvInstruction = dialog.findViewById(R.id.tv_payment_instruction);
        if (tvInstruction != null) {
            tvInstruction.setText("Silakan lakukan transfer sebesar " + currentPaketHarga + " ke salah satu rekening di bawah ini:");
        }

        LinearLayout containerBank = dialog.findViewById(R.id.container_bank_info);
        if (containerBank != null) {
            containerBank.removeAllViews();
            if (metodeList != null && !metodeList.isEmpty()) {
                for (PremiumDataResponse.Metode m : metodeList) {
                    TextView tv = new TextView(this);
                    tv.setText(m.getNamaMetode() + ": " + m.getNomorRekening() + " (a/n " + m.getAtasNama() + ")");
                    tv.setTypeface(null, android.graphics.Typeface.BOLD);
                    tv.setTextColor(getResources().getColor(R.color.black));
                    tv.setPadding(0, 0, 0, 12); 
                    containerBank.addView(tv);
                }
            } else {
                TextView tv = new TextView(this);
                tv.setText("Tidak ada metode pembayaran tersedia.");
                tv.setTextColor(getResources().getColor(R.color.text_grey));
                containerBank.addView(tv);
            }
        }

        Spinner spinner = dialog.findViewById(R.id.spinner_payment_method);
        EditText etCatatan = dialog.findViewById(R.id.et_catatan);
        
        List<String> methodNames = new ArrayList<>();
        methodNames.add("-- Pilih Metode --");
        if (metodeList != null && !metodeList.isEmpty()) {
            for (PremiumDataResponse.Metode mp : metodeList) {
                methodNames.add(mp.getNamaMetode());
            }
        } else {
             methodNames.add("Tidak ada metode");
        }

        ArrayAdapter<String> adapter = new ArrayAdapter<>(this, android.R.layout.simple_spinner_item, methodNames);
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinner.setAdapter(adapter);
        
        Button btnChooseFile = dialog.findViewById(R.id.btn_choose_file);
        tvSelectedFile = dialog.findViewById(R.id.tv_file_name); 
        
        dialog.findViewById(R.id.btn_close_dialog).setOnClickListener(v -> dialog.dismiss());
        
        btnChooseFile.setOnClickListener(v -> {
             openImagePicker();
        });

        Button btnConfirm = dialog.findViewById(R.id.btn_confirm_transfer);
        btnConfirm.setOnClickListener(v -> {
            if (spinner.getSelectedItemPosition() == 0) {
                Toast.makeText(this, "Pilih metode pembayaran terlebih dahulu", Toast.LENGTH_SHORT).show();
                return;
            }
            
            String selectedMethodName = spinner.getSelectedItem().toString();
            String selectedMethodId = "";
            
            // Cari ID dari nama metode yang dipilih
            if (metodeList != null) {
                for (PremiumDataResponse.Metode m : metodeList) {
                    if (m.getNamaMetode().equals(selectedMethodName)) {
                        selectedMethodId = m.getId();
                        break;
                    }
                }
            }
            
            if (selectedImageUri == null) {
                // Jika belum memilih file, buka picker agar pengguna langsung memilih file
                Toast.makeText(this, "Silakan pilih bukti pembayaran terlebih dahulu", Toast.LENGTH_SHORT).show();
                openImagePicker();
                return;
            }
            
            String catatan = "";
            if (etCatatan != null) {
                catatan = etCatatan.getText().toString();
            }
            
            doUploadBuktiBayar(dialog, selectedMethodId, catatan, btnConfirm);
        });

        dialog.show();
    }

    private void openImagePicker() {
        Intent intent = new Intent();
        intent.setType("image/*");
        intent.setAction(Intent.ACTION_GET_CONTENT);
        imagePickerLauncher.launch(Intent.createChooser(intent, "Pilih Bukti Transfer"));
    }

    private String getFileName(Uri uri) {
        String result = null;
        if (uri.getScheme().equals("content")) {
            try (Cursor cursor = getContentResolver().query(uri, null, null, null, null)) {
                if (cursor != null && cursor.moveToFirst()) {
                    int index = cursor.getColumnIndex(MediaStore.Images.Media.DISPLAY_NAME);
                    if(index >= 0)
                        result = cursor.getString(index);
                }
            }
        }
        if (result == null) {
            result = uri.getPath();
            int cut = result.lastIndexOf('/');
            if (cut != -1) {
                result = result.substring(cut + 1);
            }
        }
        return result;
    }

    private File getFileFromUri(Uri uri) {
        try {
            // Coba deteksi ekstensi file asli agar tipe tetap sama (jpg/png/pdf)
            String extension = ".tmp";
            String mime = getContentResolver().getType(uri);
            if (mime != null) {
                if (mime.contains("jpeg") || mime.contains("jpg")) extension = ".jpg";
                else if (mime.contains("png")) extension = ".png";
                else if (mime.contains("pdf")) extension = ".pdf";
            } else {
                String path = uri.getPath();
                if (path != null) {
                    int dot = path.lastIndexOf('.');
                    if (dot >= 0) extension = path.substring(dot);
                }
            }

            File tempFile = File.createTempFile("upload", extension, getCacheDir());
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

    private void doUploadBuktiBayar(Dialog paymentDialog, String metodeId, String catatan, Button btnConfirm) {
        SharedPreferences preferences = getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String userId = preferences.getString("user_id", "");

        if (userId.isEmpty()) {
            Toast.makeText(this, "User ID tidak ditemukan. Silakan login ulang.", Toast.LENGTH_SHORT).show();
            return;
        }
        
        if (paketPremium == null) {
            Toast.makeText(this, "Paket tidak valid. Silakan muat ulang halaman.", Toast.LENGTH_SHORT).show();
            return;
        }

        File file = getFileFromUri(selectedImageUri);
        if (file == null) {
            Toast.makeText(this, "Gagal memproses file gambar.", Toast.LENGTH_SHORT).show();
            return;
        }
        
            // Batasi sesuai keterangan layout (maks 2MB)
            long fileSizeInBytes = file.length();
            long maxBytes = 2L * 1024L * 1024L; // 2MB
            if (fileSizeInBytes > maxBytes) {
                Toast.makeText(this, "Ukuran file terlalu besar (Maks 2MB)", Toast.LENGTH_LONG).show();
                return;
            }

        btnConfirm.setText("Mengirim...");
        btnConfirm.setEnabled(false);
        paymentDialog.setCancelable(false);

        String mimeType = getContentResolver().getType(selectedImageUri);
        if (mimeType == null) mimeType = "application/octet-stream";
        RequestBody requestFile = RequestBody.create(MediaType.parse(mimeType), file);
        MultipartBody.Part body = MultipartBody.Part.createFormData("bukti_bayar", file.getName(), requestFile);
        RequestBody userIdPart = RequestBody.create(MediaType.parse("text/plain"), userId);
        RequestBody paketIdPart = RequestBody.create(MediaType.parse("text/plain"), paketPremium.getId());
        RequestBody metodeIdPart = RequestBody.create(MediaType.parse("text/plain"), metodeId);
        RequestBody catatanPart = RequestBody.create(MediaType.parse("text/plain"), catatan);

        ApiService apiService = ApiConfig.getApiService();
        // Method updated to match new signature
        Call<ResponseBody> call = apiService.uploadBuktiBayar(userIdPart, paketIdPart, metodeIdPart, catatanPart, body);

        call.enqueue(new Callback<ResponseBody>() {
            @Override
            public void onResponse(Call<ResponseBody> call, retrofit2.Response<ResponseBody> response) {
                btnConfirm.setText("Saya Sudah Transfer");
                btnConfirm.setEnabled(true);
                paymentDialog.setCancelable(true);

                if (response.isSuccessful() && response.body() != null) {
                    try {
                        String respStr = response.body().string();
                        Log.e("UploadBukti", "Raw response: " + respStr);
                        try {
                            JSONObject json = new JSONObject(respStr);
                            String status = json.optString("status");
                            String message = json.optString("message");
                            if ("success".equals(status)) {
                                paymentDialog.dismiss();
                                showSuccessDialog();
                            } else {
                                Toast.makeText(UpgradePremiumActivity.this, "Gagal: " + message, Toast.LENGTH_LONG).show();
                            }
                        } catch (JSONException je) {
                            Log.e("UploadBukti", "Invalid JSON from server", je);
                            String shortResp = respStr.length() > 500 ? respStr.substring(0, 500) + "..." : respStr;
                            Toast.makeText(UpgradePremiumActivity.this, "Server returned invalid JSON: " + shortResp, Toast.LENGTH_LONG).show();
                        }
                    } catch (IOException ioe) {
                        Log.e("UploadBukti", "Failed to read response body", ioe);
                        Toast.makeText(UpgradePremiumActivity.this, "Gagal membaca respon server.", Toast.LENGTH_LONG).show();
                    }
                } else {
                    String err = "Kesalahan Server: " + response.code();
                    try {
                        if (response.errorBody() != null) {
                            String errBody = response.errorBody().string();
                            Log.e("UploadBukti", "Error Body: " + errBody);
                            err += " - " + (errBody.length() > 300 ? errBody.substring(0, 300) + "..." : errBody);
                        }
                    } catch (IOException ioe) {
                        Log.e("UploadBukti", "Error reading errorBody", ioe);
                    }
                    Toast.makeText(UpgradePremiumActivity.this, err, Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<ResponseBody> call, Throwable t) {
                btnConfirm.setText("Saya Sudah Transfer");
                btnConfirm.setEnabled(true);
                paymentDialog.setCancelable(true);

                Toast.makeText(UpgradePremiumActivity.this, "Koneksi gagal: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                Log.e("UploadBukti", "Failure", t);
            }
        });
    }

    private void showSuccessDialog() {
        final Dialog dialog = new Dialog(this);
        dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);
        dialog.setContentView(R.layout.dialog_payment_success);

        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawable(new ColorDrawable(Color.TRANSPARENT));
            dialog.getWindow().setLayout(
                WindowManager.LayoutParams.MATCH_PARENT,
                WindowManager.LayoutParams.WRAP_CONTENT
            );
        }

        View.OnClickListener closeAction = v -> {
            dialog.dismiss();
            Intent intent = new Intent(UpgradePremiumActivity.this, MainActivity.class);
            intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
            startActivity(intent);
            finish();
        };

        dialog.findViewById(R.id.btn_close_success_dialog).setOnClickListener(closeAction);
        dialog.findViewById(R.id.btn_close_final).setOnClickListener(closeAction);

        dialog.show();
    }
}