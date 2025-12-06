package com.example.neoblue;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.cardview.widget.CardView;
import androidx.core.content.ContextCompat;
import androidx.core.view.WindowCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.neoblue.adapter.MateriAdapter;
import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.Materi;
import com.example.neoblue.models.DetailMateriResponse;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class DetailMateriActivity extends AppCompatActivity {

    private TextView tvTitleMateri;
    private RecyclerView rvMateriList;
    private MateriAdapter materiAdapter;
    private List<Materi> materiList;
    private CardView ctaPremiumBanner;
    private CardView infoPremiumUserBanner;
    private ApiService apiService;
    private String subtestId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        WindowCompat.setDecorFitsSystemWindows(getWindow(), false);
        setContentView(R.layout.activity_detail_materi);

        // Setup Header Actions
        setupHeader();

        // Initialize UI elements
        tvTitleMateri = findViewById(R.id.tv_title_materi);
        rvMateriList = findViewById(R.id.rv_materi_list);
        ctaPremiumBanner = findViewById(R.id.cta_premium_banner);
        infoPremiumUserBanner = findViewById(R.id.info_premium_user_banner);

        // Setup RecyclerView
        rvMateriList.setLayoutManager(new LinearLayoutManager(this));
        materiList = new ArrayList<>();
        materiAdapter = new MateriAdapter(this, materiList, isUserPremium());
        rvMateriList.setAdapter(materiAdapter);

        // Initialize ApiService
        apiService = ApiConfig.getApiService();

        // 1. Retrieve subtest_id from Intent
        if (getIntent().hasExtra("subtest_id")) {
            subtestId = getIntent().getStringExtra("subtest_id");
            if (subtestId == null) {
                int idInt = getIntent().getIntExtra("subtest_id", -1);
                if (idInt != -1) {
                    subtestId = String.valueOf(idInt);
                }
            }
        }

        if (subtestId == null || subtestId.isEmpty()) {
            Toast.makeText(this, "ID Subtest tidak valid / tidak ditemukan.", Toast.LENGTH_SHORT).show();
            finish();
            return;
        }

        // 2. Fetch Materi Details
        fetchMateriDetails(subtestId);

        // 3. Logika Tombol Kembali (Back) di sub-header (breadcrumb style)
        ImageView btnBack = findViewById(R.id.btn_back);
        if (btnBack != null) {
            btnBack.setOnClickListener(v -> finish());
        }

        // 4. Setup Bottom Navigation
        setupBottomNavigation();
    }

    private void setupHeader() {
        ImageView btnNotif = findViewById(R.id.btn_notifikasi);
        if (btnNotif != null) {
            btnNotif.setOnClickListener(v -> startActivity(new Intent(DetailMateriActivity.this, NotifikasiActivity.class)));
        }

        ImageView btnProfil = findViewById(R.id.btn_profil);
        if (btnProfil != null) {
            btnProfil.setOnClickListener(v -> startActivity(new Intent(DetailMateriActivity.this, ProfilActivity.class)));
        }
        // header view is present in layout; no dynamic inset handling
    }

    private void setupBottomNavigation() {
        BottomNavigationView bottomNav = findViewById(R.id.bottom_navigation);
        if (bottomNav != null) {
            bottomNav.setSelectedItemId(R.id.nav_materi);
            bottomNav.setOnNavigationItemSelectedListener(item -> {
                int itemId = item.getItemId();
                Intent intent = new Intent(DetailMateriActivity.this, MainActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);

                if (itemId == R.id.nav_materi) {
                     // Do nothing, already here (or go to main home)
                     startActivity(intent);
                     return true;
                } else if (itemId == R.id.nav_habit) {
                    intent.putExtra("fragment", "habit");
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

    private void fetchMateriDetails(String subtestId) {
        tvTitleMateri.setText("Loading...");
        
        apiService.getMateriSubtest(subtestId).enqueue(new Callback<DetailMateriResponse>() {
            @Override
            public void onResponse(@NonNull Call<DetailMateriResponse> call, @NonNull Response<DetailMateriResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    DetailMateriResponse data = response.body();
                    
                    if (data.getSubtest() != null) {
                        String subtestName = data.getSubtest().getNamaSubtest();
                        String subtestSingkatan = data.getSubtest().getSingkatan();
                        String title = subtestName + (subtestSingkatan != null && !subtestSingkatan.isEmpty() ? " (" + subtestSingkatan + ")" : "");
                        tvTitleMateri.setText(title);

                        materiList.clear();
                        if (data.getMateri() != null) {
                            materiList.addAll(data.getMateri());
                        }
                        
                        boolean isUserPremium = isUserPremium();
                        materiAdapter.setUserPremium(isUserPremium);
                        materiAdapter.notifyDataSetChanged();

                        if (materiList.isEmpty()) {
                            Toast.makeText(DetailMateriActivity.this, "Data materi kosong dari server.", Toast.LENGTH_SHORT).show();
                        }

                        if (isUserPremium) {
                            if (ctaPremiumBanner != null) ctaPremiumBanner.setVisibility(View.GONE);
                            if (infoPremiumUserBanner != null) infoPremiumUserBanner.setVisibility(View.VISIBLE);
                        } else {
                            if (infoPremiumUserBanner != null) infoPremiumUserBanner.setVisibility(View.GONE);
                            if (ctaPremiumBanner != null) {
                                ctaPremiumBanner.setVisibility(View.VISIBLE);
                                Button btnUpgradeCta = ctaPremiumBanner.findViewById(R.id.btn_upgrade_cta);
                                if (btnUpgradeCta != null) {
                                    btnUpgradeCta.setOnClickListener(v -> {
                                        startActivity(new Intent(DetailMateriActivity.this, UpgradePremiumActivity.class));
                                    });
                                }
                            }
                        }

                    } else {
                        Toast.makeText(DetailMateriActivity.this, "Data subtest kosong / format JSON tidak sesuai.", Toast.LENGTH_SHORT).show();
                        tvTitleMateri.setText("Data Error");
                    }
                } else {
                    String err = "";
                    try {
                        err = response.errorBody() != null ? response.errorBody().string() : "";
                    } catch (Exception ex) {
                        Log.e("DetailMateri", "error reading errorBody", ex);
                    }
                    Log.e("DetailMateri", "Gagal mengambil data: " + response.code() + " " + err);
                    Toast.makeText(DetailMateriActivity.this, "Gagal mengambil data: " + response.code(), Toast.LENGTH_LONG).show();
                    tvTitleMateri.setText("Error: " + response.code());
                }
            }

            @Override
            public void onFailure(@NonNull Call<DetailMateriResponse> call, @NonNull Throwable t) {
                Toast.makeText(DetailMateriActivity.this, "Koneksi Error: " + t.getMessage(), Toast.LENGTH_LONG).show();
                tvTitleMateri.setText("Connection Error");
            }
        });
    }

    private boolean isUserPremium() {
        SharedPreferences preferences = getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String userType = preferences.getString("tipe_user", "Free");
        return "Premium".equalsIgnoreCase(userType);
    }
}