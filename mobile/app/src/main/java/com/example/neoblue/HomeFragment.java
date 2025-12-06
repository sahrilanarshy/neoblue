package com.example.neoblue;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.cardview.widget.CardView;

import com.example.neoblue.adapter.HomeSubtestAdapter;
import com.example.neoblue.models.Habit;
import com.example.neoblue.models.HabitResponse;
import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.ProfileResponse;
import com.example.neoblue.models.Subtest;
import com.example.neoblue.models.SubtestResponse;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HomeFragment extends Fragment {

    private RecyclerView rvSubtests;
    private HomeSubtestAdapter subtestAdapter;
    private List<Subtest> subtestList;
    private ProgressBar pbMateri;
    // private TextView tvSubtestCount; // Removed

    // New UI components
    private ImageView ivBanner;
    private TextView tvWelcomeName;
    private TextView tvUserStatus;
    private Button btnUpgrade;
    private CardView cardMateri;
    private CardView cardJadwal;
    private CardView cardHabit;
    private CardView cardTryout;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        // Inflate layout
        return inflater.inflate(R.layout.fragment_home, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // Initialize RecyclerView and ProgressBar
        rvSubtests = view.findViewById(R.id.rv_subtests);
        // tvSubtestCount = view.findViewById(R.id.tv_subtest_count); // Removed
        pbMateri = view.findViewById(R.id.pb_materi);

        // Initialize new components
        ivBanner = view.findViewById(R.id.iv_banner);
        tvWelcomeName = view.findViewById(R.id.tv_welcome_name);
        tvUserStatus = view.findViewById(R.id.tv_user_status);
        btnUpgrade = view.findViewById(R.id.btn_upgrade_premium);

        // Feature cards
        cardMateri = view.findViewById(R.id.card_materi);
        cardJadwal = view.findViewById(R.id.card_jadwal);
        cardHabit = view.findViewById(R.id.card_habit);
        cardTryout = view.findViewById(R.id.card_tryout);

        rvSubtests.setLayoutManager(new LinearLayoutManager(getContext()));
        subtestList = new ArrayList<>();
        // Menggunakan HomeSubtestAdapter yang baru dibuat
        subtestAdapter = new HomeSubtestAdapter(getContext(), subtestList);
        rvSubtests.setAdapter(subtestAdapter);

        // Fetch data from API
        fetchSubtests();
        fetchUserProfile();

        // Setup other listeners
        if (btnUpgrade != null) {
            btnUpgrade.setOnClickListener(v -> {
                Intent intent = new Intent(getActivity(), UpgradePremiumActivity.class);
                startActivity(intent);
            });
        }

        // Card click listeners - navigate via MainActivity using fragment extra
        if (cardMateri != null) {
            cardMateri.setOnClickListener(v -> {
                Intent intent = new Intent(getActivity(), MainActivity.class);
                intent.putExtra("fragment", "materi");
                startActivity(intent);
            });
        }

        if (cardJadwal != null) {
            cardJadwal.setOnClickListener(v -> {
                Intent intent = new Intent(getActivity(), MainActivity.class);
                intent.putExtra("fragment", "jadwal");
                startActivity(intent);
            });
        }

        if (cardHabit != null) {
            cardHabit.setOnClickListener(v -> {
                Intent intent = new Intent(getActivity(), MainActivity.class);
                intent.putExtra("fragment", "habit");
                startActivity(intent);
            });
        }

        if (cardTryout != null) {
            cardTryout.setOnClickListener(v -> {
                Intent intent = new Intent(getActivity(), MainActivity.class);
                intent.putExtra("fragment", "tryout");
                startActivity(intent);
            });
        }

    }

    private void fetchUserProfile() {
        if (getActivity() == null) return;

        SharedPreferences preferences = getActivity().getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String userId = preferences.getString("user_id", "");

        if (userId.isEmpty()) return;

        ApiService apiService = ApiConfig.getApiService();
        apiService.getProfile(userId).enqueue(new Callback<ProfileResponse>() {
            @Override
            public void onResponse(@NonNull Call<ProfileResponse> call, @NonNull Response<ProfileResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    ProfileResponse profileResponse = response.body();
                    if ("success".equals(profileResponse.getStatus()) && profileResponse.getData() != null) {
                        updateUI(profileResponse.getData());
                    }
                }
            }

            @Override
            public void onFailure(@NonNull Call<ProfileResponse> call, @NonNull Throwable t) {
                // Silently fail or log
            }
        });
    }

    private void updateUI(ProfileResponse.Data userData) {
        if (getContext() == null) return;

        String nama = userData.getNama();
        tvWelcomeName.setText("Selamat Datang, " + nama + "!");

        String tipeUser = userData.getTipeUser(); // "Free" or "Premium"

        if ("Premium".equalsIgnoreCase(tipeUser)) {
            ivBanner.setImageResource(R.drawable.bg_premium);
            tvUserStatus.setText("Premium");
            btnUpgrade.setVisibility(View.GONE);
        } else {
            ivBanner.setImageResource(R.drawable.bg_free);
            tvUserStatus.setText("Gratis");
            btnUpgrade.setVisibility(View.VISIBLE);
        }
    }

    private void fetchSubtests() {
        showLoading(true);
        ApiService apiService = ApiConfig.getApiService();
        apiService.getSubtests().enqueue(new Callback<SubtestResponse>() {
            @Override
            public void onResponse(@NonNull Call<SubtestResponse> call, @NonNull Response<SubtestResponse> response) {
                showLoading(false);
                if (response.isSuccessful() && response.body() != null) {
                    SubtestResponse subtestResponse = response.body();
                    if ("success".equals(subtestResponse.getStatus())) {
                        subtestList.clear();
                        if (subtestResponse.getData() != null) {
                            subtestList.addAll(subtestResponse.getData());
                            // Removed subtest count update
                        }
                        subtestAdapter.notifyDataSetChanged();
                    } else {
                        Toast.makeText(getContext(), "Gagal memuat materi: " + subtestResponse.getMessage(), Toast.LENGTH_LONG).show();
                    }
                } else {
                    Toast.makeText(getContext(), "Gagal memuat materi: Server error", Toast.LENGTH_LONG).show();
                }
            }

            @Override
            public void onFailure(@NonNull Call<SubtestResponse> call, @NonNull Throwable t) {
                showLoading(false);
                Toast.makeText(getContext(), "Gagal memuat materi: " + t.getMessage(), Toast.LENGTH_LONG).show();
            }
        });
    }

    private void showLoading(boolean isLoading) {
        if (pbMateri != null) {
            pbMateri.setVisibility(isLoading ? View.VISIBLE : View.GONE);
        }
    }
}
