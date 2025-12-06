package com.example.neoblue;

import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.WindowCompat;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import com.example.neoblue.utils.NotificationUtils;

public class NotifikasiActivity extends AppCompatActivity {

    private RecyclerView rvNotifikasi;
    private ProgressBar pbLoading;
    private TextView tvEmpty;
    private SwipeRefreshLayout swipeRefresh;
    private com.example.neoblue.adapter.NotificationAdapter adapter;
    private java.util.List<com.example.neoblue.models.NotificationResponse.Notification> list = new java.util.ArrayList<>();

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        WindowCompat.setDecorFitsSystemWindows(getWindow(), false);
        setContentView(R.layout.activity_notifikasi);

        // Initialize UI
        rvNotifikasi = findViewById(R.id.rv_notifikasi);
        pbLoading = findViewById(R.id.pb_loading);
        tvEmpty = findViewById(R.id.tv_empty);
        swipeRefresh = findViewById(R.id.swipe_refresh);
        
        ImageView btnBack = findViewById(R.id.btn_back_notif);
        if (btnBack != null) {
            btnBack.setOnClickListener(v -> finish());
        }

        // Setup RecyclerView
        rvNotifikasi.setLayoutManager(new LinearLayoutManager(this));
        adapter = new com.example.neoblue.adapter.NotificationAdapter(this, list);
        rvNotifikasi.setAdapter(adapter);

        // Setup SwipeRefresh
        swipeRefresh.setOnRefreshListener(this::loadData);

        // Load Data Initial
        loadData();
    }

    private void loadData() {
        pbLoading.setVisibility(View.VISIBLE);
        
        // Ambil user_id dari SharedPreferences agar API mengembalikan notifikasi khusus user
        android.content.SharedPreferences preferences = getSharedPreferences("user_session", MODE_PRIVATE);
        String userId = preferences.getString("user_id", "");

        ApiService apiService = ApiConfig.getApiService();
        Call<com.example.neoblue.models.NotificationResponse> call = apiService.getNotifikasi(userId.isEmpty() ? null : userId);

        call.enqueue(new Callback<com.example.neoblue.models.NotificationResponse>() {
            @Override
            public void onResponse(Call<com.example.neoblue.models.NotificationResponse> call, Response<com.example.neoblue.models.NotificationResponse> response) {
                pbLoading.setVisibility(View.GONE);
                swipeRefresh.setRefreshing(false);

                if (response.isSuccessful() && response.body() != null) {
                    if ("success".equals(response.body().getStatus())) {
                        java.util.List<com.example.neoblue.models.NotificationResponse.Notification> data = response.body().getData();
                        if (data != null && !data.isEmpty()) {
                            list.clear();
                            list.addAll(data);
                            adapter.notifyDataSetChanged();

                            // Hitung unread: personal unread + semua pengumuman global yang belum disembunyikan
                            int unread = 0;
                            for (com.example.neoblue.models.NotificationResponse.Notification it : data) {
                                String isGlobal = it.getIs_global();
                                String isRead = it.getIs_read();
                                if (isGlobal != null && isGlobal.equals("1")) {
                                    // pengumuman global dianggap perlu diberi tanda sampai user menyembunyikannya
                                    unread++;
                                } else if (isRead != null && isRead.equals("0")) {
                                    // personal unread
                                    unread++;
                                }
                            }
                            // Simpan sementara ke SharedPreferences untuk badge
                            android.content.SharedPreferences prefs = getSharedPreferences("user_session", MODE_PRIVATE);
                            prefs.edit().putInt("notif_unread_count", unread).apply();
                            // Update badge di header
                            NotificationUtils.updateBadgeFromPrefs(NotifikasiActivity.this);

                            // Tandai semua notifikasi unread sebagai dibaca di server, tunggu semua panggilan selesai
                            com.example.neoblue.api.ApiService api = com.example.neoblue.api.ApiConfig.getApiService();

                            // Kumpulkan list item yang perlu ditandai
                            java.util.List<com.example.neoblue.models.NotificationResponse.Notification> toMark = new java.util.ArrayList<>();
                            for (com.example.neoblue.models.NotificationResponse.Notification it : data) {
                                String nid = it.getId();
                                String isRead = it.getIs_read();
                                if (nid == null) continue;
                                boolean unreadItem = (isRead == null || isRead.equals("0"));
                                if (unreadItem) toMark.add(it);
                            }

                            if (toMark.isEmpty()) {
                                // Tidak ada yang perlu ditandai
                                return;
                            }

                            final java.util.concurrent.atomic.AtomicInteger remaining = new java.util.concurrent.atomic.AtomicInteger(toMark.size());
                            final String uid = getSharedPreferences("user_session", MODE_PRIVATE).getString("user_id", "");

                            for (com.example.neoblue.models.NotificationResponse.Notification it : toMark) {
                                String nid = it.getId();
                                String isGlobal = it.getIs_global();

                                if (uid == null || uid.isEmpty()) {
                                    // tidak punya user id, anggap selesai satu per satu
                                    if (remaining.decrementAndGet() == 0) {
                                        // semua selesai
                                        prefs.edit().putInt("notif_unread_count", 0).apply();
                                        runOnUiThread(() -> NotificationUtils.updateBadgeFromPrefs(NotifikasiActivity.this));
                                    }
                                    continue;
                                }

                                if (isGlobal != null && isGlobal.equals("1")) {
                                    api.markPengumumanRead(uid, nid).enqueue(new retrofit2.Callback<okhttp3.ResponseBody>() {
                                        @Override
                                        public void onResponse(retrofit2.Call<okhttp3.ResponseBody> call, retrofit2.Response<okhttp3.ResponseBody> response) {
                                            if (remaining.decrementAndGet() == 0) {
                                                prefs.edit().putInt("notif_unread_count", 0).apply();
                                                runOnUiThread(() -> NotificationUtils.updateBadgeFromPrefs(NotifikasiActivity.this));
                                            }
                                        }

                                        @Override
                                        public void onFailure(retrofit2.Call<okhttp3.ResponseBody> call, Throwable t) {
                                            if (remaining.decrementAndGet() == 0) {
                                                prefs.edit().putInt("notif_unread_count", 0).apply();
                                                runOnUiThread(() -> NotificationUtils.updateBadgeFromPrefs(NotifikasiActivity.this));
                                            }
                                        }
                                    });
                                } else {
                                    // numeric personal id only
                                    try {
                                        Integer.parseInt(nid);
                                        api.markNotificationRead(uid, nid).enqueue(new retrofit2.Callback<okhttp3.ResponseBody>() {
                                            @Override
                                            public void onResponse(retrofit2.Call<okhttp3.ResponseBody> call, retrofit2.Response<okhttp3.ResponseBody> response) {
                                                if (remaining.decrementAndGet() == 0) {
                                                    prefs.edit().putInt("notif_unread_count", 0).apply();
                                                    runOnUiThread(() -> NotificationUtils.updateBadgeFromPrefs(NotifikasiActivity.this));
                                                }
                                            }

                                            @Override
                                            public void onFailure(retrofit2.Call<okhttp3.ResponseBody> call, Throwable t) {
                                                if (remaining.decrementAndGet() == 0) {
                                                    prefs.edit().putInt("notif_unread_count", 0).apply();
                                                    runOnUiThread(() -> NotificationUtils.updateBadgeFromPrefs(NotifikasiActivity.this));
                                                }
                                            }
                                        });
                                    } catch (Exception ex) {
                                        if (remaining.decrementAndGet() == 0) {
                                            prefs.edit().putInt("notif_unread_count", 0).apply();
                                            runOnUiThread(() -> NotificationUtils.updateBadgeFromPrefs(NotifikasiActivity.this));
                                        }
                                    }
                                }
                            }

                            rvNotifikasi.setVisibility(View.VISIBLE);
                            tvEmpty.setVisibility(View.GONE);
                        } else {
                            showEmptyState();
                        }
                    } else {
                        Toast.makeText(NotifikasiActivity.this, "Gagal memuat data", Toast.LENGTH_SHORT).show();
                        showEmptyState();
                    }
                } else {
                    Toast.makeText(NotifikasiActivity.this, "Terjadi kesalahan server: " + response.code(), Toast.LENGTH_SHORT).show();
                    showEmptyState();
                }
            }

            @Override
            public void onFailure(Call<com.example.neoblue.models.NotificationResponse> call, Throwable t) {
                pbLoading.setVisibility(View.GONE);
                swipeRefresh.setRefreshing(false);
                Toast.makeText(NotifikasiActivity.this, "Koneksi gagal: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                showEmptyState();
            }
        });
    }

    @Override
    protected void onResume() {
        super.onResume();
        // Perbarui badge setiap kali activity muncul
        NotificationUtils.updateBadgeFromPrefs(this);
    }

    private void showEmptyState() {
        if (list.isEmpty()) {
            rvNotifikasi.setVisibility(View.GONE);
            tvEmpty.setVisibility(View.VISIBLE);
        }
    }
}
