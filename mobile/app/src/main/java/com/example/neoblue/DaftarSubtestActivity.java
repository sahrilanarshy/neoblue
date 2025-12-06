package com.example.neoblue;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.WindowCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.example.neoblue.adapter.SubtestAdapter;
import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.MateriTryoutResponse;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import com.google.gson.Gson;

public class DaftarSubtestActivity extends AppCompatActivity {

    private RecyclerView rvSubtest;
    private ProgressBar pbLoading;
    private SwipeRefreshLayout swipeRefresh;
    private TextView tvJudulTryout;
    private SubtestAdapter adapter;
    private List<MateriTryoutResponse.Materi> list = new ArrayList<>();
    private String tryoutId;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        WindowCompat.setDecorFitsSystemWindows(getWindow(), false);
        setContentView(R.layout.activity_daftar_subtest);
        
        // Setup Header
        setupHeader();

        // Ambil ID Tryout dari Intent
        tryoutId = getIntent().getStringExtra("TRYOUT_ID");

        // Initialize UI
        rvSubtest = findViewById(R.id.rv_subtest);
        pbLoading = findViewById(R.id.pb_loading);
        swipeRefresh = findViewById(R.id.swipe_refresh);
        tvJudulTryout = findViewById(R.id.tv_judul_tryout);

        // Setup RecyclerView
        rvSubtest.setLayoutManager(new LinearLayoutManager(this));
        adapter = new SubtestAdapter(this, list, tryoutId);
        rvSubtest.setAdapter(adapter);

        // Setup SwipeRefresh
        swipeRefresh.setOnRefreshListener(this::loadData);

        // Setup Bottom Navigation
        setupBottomNav();
    }

    @Override
    protected void onResume() {
        super.onResume();
        loadData();
    }

    private void setupHeader() {
        ImageView btnNotif = findViewById(R.id.btn_notifikasi);
        if (btnNotif != null) {
            btnNotif.setOnClickListener(v -> startActivity(new Intent(DaftarSubtestActivity.this, NotifikasiActivity.class)));
        }

        ImageView btnProfil = findViewById(R.id.btn_profil);
        if (btnProfil != null) {
            btnProfil.setOnClickListener(v -> startActivity(new Intent(DaftarSubtestActivity.this, ProfilActivity.class)));
        }
        // header view is present in layout; no dynamic inset handling
    }

    private void loadData() {
        if (tryoutId == null) {
            Toast.makeText(this, "ID Tryout tidak valid", Toast.LENGTH_SHORT).show();
            return;
        }

        pbLoading.setVisibility(View.VISIBLE);

        // Ambil User ID dari Shared Preferences
        SharedPreferences sharedPreferences = getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String userId = sharedPreferences.getString("user_id", "");
        
        ApiService apiService = ApiConfig.getApiService();
        Call<MateriTryoutResponse> call = apiService.getMateriTryout(tryoutId, userId);

        call.enqueue(new Callback<MateriTryoutResponse>() {
            @Override
            public void onResponse(Call<MateriTryoutResponse> call, Response<MateriTryoutResponse> response) {
                pbLoading.setVisibility(View.GONE);
                swipeRefresh.setRefreshing(false);

                // Debug: log raw response for troubleshooting
                try {
                    if (response.body() != null) {
                        String raw = new Gson().toJson(response.body());
                        android.util.Log.d("DaftarSubtestActivity", "API response body: " + raw);
                    } else if (response.errorBody() != null) {
                        android.util.Log.d("DaftarSubtestActivity", "API error body: " + response.errorBody().string());
                    } else {
                        android.util.Log.d("DaftarSubtestActivity", "API response empty with code " + response.code());
                    }
                } catch (Exception ex) {
                    android.util.Log.e("DaftarSubtestActivity", "Error logging response body", ex);
                }

                if (response.isSuccessful() && response.body() != null) {
                    MateriTryoutResponse body = response.body();
                    if ("success".equals(body.getStatus())) {
                        // Set Judul Tryout dari API
                        if (body.getTryout() != null && body.getTryout().getNamaTryout() != null) {
                            tvJudulTryout.setText("Detail Tryout: " + body.getTryout().getNamaTryout());
                        }

                        List<MateriTryoutResponse.Materi> data = body.getData();
                        if (data != null) {
                            list.clear();
                            list.addAll(data);
                            adapter.notifyDataSetChanged();
                        }
                    } else {
                        String errorMsg = (body.getStatus() != null) ? body.getStatus() : "Gagal memuat data";
                        Toast.makeText(DaftarSubtestActivity.this, errorMsg, Toast.LENGTH_SHORT).show();
                    }
                } else {
                    String errorMsg = "Server Error: " + response.code();
                     try {
                        if (response.errorBody() != null) {
                            errorMsg += "\n" + response.errorBody().string(); 
                        }
                    } catch (Exception e) { e.printStackTrace(); }
                    Toast.makeText(DaftarSubtestActivity.this, errorMsg, Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(Call<MateriTryoutResponse> call, Throwable t) {
                pbLoading.setVisibility(View.GONE);
                swipeRefresh.setRefreshing(false);
                Toast.makeText(DaftarSubtestActivity.this, "Koneksi gagal: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void setupBottomNav() {
        BottomNavigationView bottomNav = findViewById(R.id.bottom_navigation_detail);
        if (bottomNav != null) {
            bottomNav.setSelectedItemId(R.id.nav_tryout);
            bottomNav.setOnNavigationItemSelectedListener(item -> {
                int itemId = item.getItemId();
                Intent intent = new Intent(DaftarSubtestActivity.this, MainActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);

                if (itemId == R.id.nav_tryout) {
                     intent.putExtra("fragment", "tryout");
                     startActivity(intent);
                     return true;
                } else if (itemId == R.id.nav_materi) {
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
                } else if (itemId == R.id.nav_jadwal) {
                    intent.putExtra("fragment", "jadwal");
                    startActivity(intent);
                    return true;
                }
                return false;
            });
        }
    }
}