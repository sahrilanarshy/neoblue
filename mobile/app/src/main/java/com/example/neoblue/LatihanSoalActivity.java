package com.example.neoblue;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.WindowCompat;

import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.HabitDetailResponse;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class LatihanSoalActivity extends AppCompatActivity {

    private TextView tvJudul, tvTanggal, tvIsiBacaan, tvPertanyaan, tvKunciJawaban, tvPembahasan, btnShowAnswer;
    private TextView tvOpsiA, tvOpsiB, tvOpsiC, tvOpsiD, tvOpsiE;
    private LinearLayout layoutSoalContainer, layoutPembahasan, layoutOpsiJawaban;
    private String habitId, habitType;
    private ImageView btnBack;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        WindowCompat.setDecorFitsSystemWindows(getWindow(), false);
        setContentView(R.layout.activity_latihan_soal);
        
        setupHeader();

        // 1. Initialize Views
        tvJudul = findViewById(R.id.tv_judul_detail);
        tvTanggal = findViewById(R.id.tv_tanggal_detail);
        tvIsiBacaan = findViewById(R.id.tv_isi_detail);
        
        layoutSoalContainer = findViewById(R.id.layout_soal_container);
        tvPertanyaan = findViewById(R.id.tv_pertanyaan);
        layoutOpsiJawaban = findViewById(R.id.layout_opsi_jawaban);
        
        tvOpsiA = findViewById(R.id.tv_opsi_a);
        tvOpsiB = findViewById(R.id.tv_opsi_b);
        tvOpsiC = findViewById(R.id.tv_opsi_c);
        tvOpsiD = findViewById(R.id.tv_opsi_d);
        tvOpsiE = findViewById(R.id.tv_opsi_e);
        
        btnShowAnswer = findViewById(R.id.btn_show_answer);
        
        layoutPembahasan = findViewById(R.id.layout_pembahasan);
        tvKunciJawaban = findViewById(R.id.tv_kunci_jawaban);
        tvPembahasan = findViewById(R.id.tv_pembahasan);

        // Tombol Back manual jika layout_header tidak otomatis (tapi karena kita pakai include, ini untuk keamanan)
        btnBack = findViewById(R.id.btn_back); // Jika ada tombol back lain selain di header
        if (btnBack != null) {
            btnBack.setOnClickListener(v -> finish());
        }

        // 2. Get Data from Intent
        if (getIntent().hasExtra("HABIT_ID")) {
            habitId = getIntent().getStringExtra("HABIT_ID");
            habitType = getIntent().getStringExtra("HABIT_TYPE"); // "Soal" or "Bacaan"
        }

        if (habitId == null) {
            Toast.makeText(this, "ID Habit tidak ditemukan", Toast.LENGTH_SHORT).show();
            finish();
            return;
        }

        // 3. Fetch Detail Data
        fetchHabitDetail(habitId);

        // 4. Listener for Show Answer
        if (btnShowAnswer != null) {
            btnShowAnswer.setOnClickListener(v -> {
                if (layoutPembahasan.getVisibility() == View.GONE) {
                    layoutPembahasan.setVisibility(View.VISIBLE);
                    btnShowAnswer.setText("Sembunyikan Jawaban");
                } else {
                    layoutPembahasan.setVisibility(View.GONE);
                    btnShowAnswer.setText("Lihat Kunci Jawaban");
                }
            });
        }

        // 5. Setup Bottom Navigation
        setupBottomNav();
    }

    private void setupHeader() {
        ImageView btnNotif = findViewById(R.id.btn_notifikasi);
        if (btnNotif != null) {
            btnNotif.setOnClickListener(v -> startActivity(new Intent(LatihanSoalActivity.this, NotifikasiActivity.class)));
        }

        ImageView btnProfil = findViewById(R.id.btn_profil);
        if (btnProfil != null) {
            btnProfil.setOnClickListener(v -> startActivity(new Intent(LatihanSoalActivity.this, ProfilActivity.class)));
        }
        // header view is present in layout; no dynamic inset handling
    }

    private void setupBottomNav() {
        BottomNavigationView bottomNav = findViewById(R.id.bottom_navigation);
        if (bottomNav != null) {
            bottomNav.setSelectedItemId(R.id.nav_habit);
            bottomNav.setOnNavigationItemSelectedListener(item -> {
                int itemId = item.getItemId();
                Intent intent = new Intent(LatihanSoalActivity.this, MainActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);

                if (itemId == R.id.nav_habit) {
                    intent.putExtra("fragment", "habit");
                    startActivity(intent);
                    return true;
                } else if (itemId == R.id.nav_materi) {
                    startActivity(intent);
                    return true;
                } else if (itemId == R.id.nav_short) {
                    intent.putExtra("fragment", "short");
                    startActivity(intent);
                    return true;
                } else if (itemId == R.id.nav_tryout) {
                    intent.putExtra("fragment", "tryout");
                    startActivity(intent);
                    return true;
                } else if (itemId == R.id.nav_jadwal) {
                    intent.putExtra("fragment", "jadwal");
                    startActivity(intent);
                    return true;
                }
                return false;
            });
        }
    }

    private void fetchHabitDetail(String id) {
        SharedPreferences preferences = getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String userId = preferences.getString("user_id", "");
        if (userId.isEmpty()) {
            userId = "1"; 
        }

        ApiService apiService = ApiConfig.getApiService();
        apiService.getHabitDetail(id, userId).enqueue(new Callback<HabitDetailResponse>() {
            @Override
            public void onResponse(@NonNull Call<HabitDetailResponse> call, @NonNull Response<HabitDetailResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    HabitDetailResponse result = response.body();
                    if ("success".equals(result.getStatus()) && result.getData() != null) {
                        HabitDetailResponse.HabitDetailData data = result.getData();
                        
                        // Set common data
                        tvJudul.setText(data.getJudul());
                        tvTanggal.setText(data.getTanggal() + " - " + (data.getSubtest() != null ? data.getSubtest() : ""));

                        // Check Type
                        String type = data.getTipe(); 
                        if (type == null || type.isEmpty()) type = habitType; 

                        boolean isSoal = type != null && (type.equalsIgnoreCase("soal") || type.equalsIgnoreCase("quiz") || type.equalsIgnoreCase("Soal"));

                        if (isSoal) {
                            tvIsiBacaan.setVisibility(View.GONE);
                            layoutSoalContainer.setVisibility(View.VISIBLE);

                            if (data.getSoal() != null && !data.getSoal().isEmpty()) {
                                HabitDetailResponse.Soal s = data.getSoal().get(0);
                                tvPertanyaan.setText(s.getPertanyaan());

                                // Gunakan TextView biasa, bukan RadioButton
                                tvOpsiA.setText("A. " + (s.getPilihanA() != null ? s.getPilihanA() : "-"));
                                tvOpsiB.setText("B. " + (s.getPilihanB() != null ? s.getPilihanB() : "-"));
                                tvOpsiC.setText("C. " + (s.getPilihanC() != null ? s.getPilihanC() : "-"));
                                tvOpsiD.setText("D. " + (s.getPilihanD() != null ? s.getPilihanD() : "-"));
                                tvOpsiE.setText("E. " + (s.getPilihanE() != null ? s.getPilihanE() : "-"));

                                tvKunciJawaban.setText("Jawaban: " + (s.getKunciJawaban() != null ? s.getKunciJawaban() : "-"));
                                tvPembahasan.setText(s.getPembahasan() != null ? s.getPembahasan() : "Tidak ada pembahasan.");
                            } else {
                                tvPertanyaan.setText("Tidak ada soal untuk habit ini.");
                                tvOpsiA.setText("A. -"); tvOpsiB.setText("B. -"); tvOpsiC.setText("C. -"); tvOpsiD.setText("D. -"); tvOpsiE.setText("E. -");
                                tvKunciJawaban.setText("Jawaban: -");
                                tvPembahasan.setText("Tidak ada pembahasan.");
                            }

                        } else {
                            tvIsiBacaan.setVisibility(View.VISIBLE);
                            layoutSoalContainer.setVisibility(View.GONE);
                            
                            String content = data.getIsi();
                            if (content == null) content = "Konten bacaan kosong.";

                            if (android.os.Build.VERSION.SDK_INT >= android.os.Build.VERSION_CODES.N) {
                                tvIsiBacaan.setText(android.text.Html.fromHtml(content, android.text.Html.FROM_HTML_MODE_COMPACT));
                            } else {
                                tvIsiBacaan.setText(android.text.Html.fromHtml(content));
                            }
                        }

                    } else {
                        Toast.makeText(LatihanSoalActivity.this, "Data tidak ditemukan: " + result.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                } else {
                    if (response.code() == 403) {
                        Toast.makeText(LatihanSoalActivity.this, "Akses Ditolak (403). Cek Login.", Toast.LENGTH_LONG).show();
                    } else {
                        Toast.makeText(LatihanSoalActivity.this, "Gagal memuat data: " + response.code(), Toast.LENGTH_SHORT).show();
                    }
                }
            }

            @Override
            public void onFailure(@NonNull Call<HabitDetailResponse> call, @NonNull Throwable t) {
                Toast.makeText(LatihanSoalActivity.this, "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}