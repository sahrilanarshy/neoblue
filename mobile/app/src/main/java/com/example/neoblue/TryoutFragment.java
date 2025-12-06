package com.example.neoblue;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.cardview.widget.CardView;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;

import com.example.neoblue.adapter.TryoutAdapter;
import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.TryoutResponse;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;
import java.util.Locale;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class TryoutFragment extends Fragment {

    private RecyclerView rvTryout;
    private ProgressBar pbLoading;
    private TextView tvEmpty;
    private SwipeRefreshLayout swipeRefresh;
    private LinearLayout layoutContent;

    // Views for Top Card (Tryout Terbaru)
    private CardView cardTryoutTerbaru;
    private TextView tvJudulTerbaru, tvTanggalTerbaru;
    private Button btnActionTerbaru;
    private TextView tvHeaderTerbaru, tvHeaderSemua;

    private TryoutAdapter adapter;
    private List<TryoutResponse.Tryout> list = new ArrayList<>();

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_tryout, container, false);

        // Initialize UI
        rvTryout = view.findViewById(R.id.rv_tryout);
        pbLoading = view.findViewById(R.id.pb_loading);
        tvEmpty = view.findViewById(R.id.tv_empty);
        swipeRefresh = view.findViewById(R.id.swipe_refresh);
        layoutContent = view.findViewById(R.id.layout_content);

        // Initialize Top Card Views
        cardTryoutTerbaru = view.findViewById(R.id.card_tryout_terbaru);
        tvJudulTerbaru = view.findViewById(R.id.tv_judul_terbaru);
        tvTanggalTerbaru = view.findViewById(R.id.tv_tanggal_terbaru);
        btnActionTerbaru = view.findViewById(R.id.btn_action_terbaru);
        tvHeaderTerbaru = view.findViewById(R.id.tv_header_terbaru);
        tvHeaderSemua = view.findViewById(R.id.tv_header_semua);

        // Setup RecyclerView
        rvTryout.setLayoutManager(new LinearLayoutManager(getContext()));
        rvTryout.setNestedScrollingEnabled(false);
        
        // Setup SwipeRefresh
        swipeRefresh.setOnRefreshListener(this::loadData);

        // Load Data
        loadData();

        return view;
    }

    private void loadData() {
        pbLoading.setVisibility(View.VISIBLE);
        layoutContent.setVisibility(View.GONE); // Hide content while loading
        tvEmpty.setVisibility(View.GONE);
        
        ApiService apiService = ApiConfig.getApiService();
        Call<TryoutResponse> call = apiService.getTryouts();

        call.enqueue(new Callback<TryoutResponse>() {
            @Override
            public void onResponse(Call<TryoutResponse> call, Response<TryoutResponse> response) {
                if (getContext() == null) return;
                
                pbLoading.setVisibility(View.GONE);
                swipeRefresh.setRefreshing(false);

                if (response.isSuccessful() && response.body() != null) {
                    TryoutResponse resp = response.body();
                    boolean isPremium = resp.isPremium();
                    
                    // Fallback cek local storage
                    if (!isPremium) {
                        SharedPreferences prefs = getContext().getSharedPreferences("user_session", Context.MODE_PRIVATE);
                        String tipeUser = prefs.getString("tipe_user", "Free");
                        if ("Premium".equalsIgnoreCase(tipeUser)) {
                            isPremium = true;
                        }
                    }

                    List<TryoutResponse.Tryout> data = resp.getTryouts();
                    if (data != null && !data.isEmpty()) {
                        list.clear();
                        list.addAll(data);
                        
                        // Setup Top Card (Latest Tryout) - Assuming first item is latest
                        TryoutResponse.Tryout latestTryout = list.get(0);
                        setupLatestTryout(latestTryout, isPremium);

                        // Setup RecyclerView (All Tryouts)
                        // Menampilkan semua tryout di list bawah juga
                        adapter = new TryoutAdapter(getContext(), list, isPremium);
                        rvTryout.setAdapter(adapter);
                        
                        layoutContent.setVisibility(View.VISIBLE);
                    } else {
                        showEmptyState();
                    }
                } else {
                    Toast.makeText(getContext(), "Gagal memuat data tryout", Toast.LENGTH_SHORT).show();
                    showEmptyState();
                }
            }

            @Override
            public void onFailure(Call<TryoutResponse> call, Throwable t) {
                if (getContext() == null) return;
                
                pbLoading.setVisibility(View.GONE);
                swipeRefresh.setRefreshing(false);
                Toast.makeText(getContext(), "Koneksi gagal: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                showEmptyState();
            }
        });
    }

    private void setupLatestTryout(TryoutResponse.Tryout tryout, boolean isPremiumUser) {
        if (getContext() == null) return;

        tvJudulTerbaru.setText(tryout.getNamaTryout());
        tvTanggalTerbaru.setText("Mulai Tanggal " + tryout.getTanggalMulai());

        String tipe = tryout.getTipe();
        boolean isTryoutGratis = "Gratis".equalsIgnoreCase(tipe) || "Free".equalsIgnoreCase(tipe);
        boolean isUpcoming = false;
        boolean isExpired = false;
        
        try {
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
            Date dateMulai = sdf.parse(tryout.getTanggalMulai());
            Date dateSelesai = sdf.parse(tryout.getTanggalSelesai());
            Date today = sdf.parse(sdf.format(new Date())); // normalize to date only
            if (dateMulai != null && dateMulai.after(today)) {
                isUpcoming = true;
            }
            if (dateSelesai != null && dateSelesai.before(today)) {
                isExpired = true;
            }
        } catch (ParseException e) {
            e.printStackTrace();
        }

        btnActionTerbaru.setVisibility(View.VISIBLE);
        btnActionTerbaru.setEnabled(true);
        btnActionTerbaru.setTextColor(Color.WHITE);

        if (isExpired) {
            btnActionTerbaru.setText("Telah Berakhir");
            btnActionTerbaru.setBackgroundColor(Color.parseColor("#F44336")); // Red
            btnActionTerbaru.setEnabled(false);
        } else if (isUpcoming) {
            btnActionTerbaru.setText("Segera Dimulai");
            btnActionTerbaru.setBackgroundColor(Color.parseColor("#9E9E9E"));
            btnActionTerbaru.setEnabled(false);
        } else {
            if (isPremiumUser) {
                // Premium User
                btnActionTerbaru.setText("Mulai Kerjakan");
                btnActionTerbaru.setBackgroundTintList(getContext().getResources().getColorStateList(R.color.neo_blue));
                
                btnActionTerbaru.setOnClickListener(v -> {
                    Intent intent = new Intent(getContext(), DaftarSubtestActivity.class);
                    intent.putExtra("TRYOUT_ID", tryout.getId());
                    startActivity(intent);
                });
            } else {
                // Free User
                if (isTryoutGratis) {
                    btnActionTerbaru.setText("Kerjakan Sekarang");
                    btnActionTerbaru.setBackgroundTintList(getContext().getResources().getColorStateList(R.color.neo_blue));
                    
                    btnActionTerbaru.setOnClickListener(v -> {
                        Intent intent = new Intent(getContext(), DaftarSubtestActivity.class);
                        intent.putExtra("TRYOUT_ID", tryout.getId());
                        startActivity(intent);
                    });
                } else {
                    btnActionTerbaru.setText("Upgrade Premium");
                    btnActionTerbaru.setBackgroundColor(Color.parseColor("#4DD0E1")); // Cyan
                    
                    btnActionTerbaru.setOnClickListener(v -> {
                        Intent intent = new Intent(getContext(), UpgradePremiumActivity.class);
                        startActivity(intent);
                    });
                }
            }
        }
    }

    private void showEmptyState() {
        layoutContent.setVisibility(View.GONE);
        tvEmpty.setVisibility(View.VISIBLE);
    }
}