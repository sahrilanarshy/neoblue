package com.example.neoblue;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.neoblue.adapter.HabitAdapter;
import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.Habit;
import com.example.neoblue.models.HabitResponse;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HabitFragment extends Fragment {

    private RecyclerView rvHabit;
    private HabitAdapter habitAdapter;
    private List<Habit> habitList;
    private ProgressBar pbLoading;
    private TextView tvHabitDate;
    private TextView tvEmptyState;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_habit, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // Initialize Views
        rvHabit = view.findViewById(R.id.rv_habit);
        pbLoading = view.findViewById(R.id.pb_loading);
        tvHabitDate = view.findViewById(R.id.tv_habit_today_date);
        tvEmptyState = view.findViewById(R.id.tv_empty_state);

        // Setup RecyclerView
        rvHabit.setLayoutManager(new LinearLayoutManager(getContext()));
        habitList = new ArrayList<>();
        habitAdapter = new HabitAdapter(getContext(), habitList);
        rvHabit.setAdapter(habitAdapter);

        // We will display group dates in each item (adapter). Hide the fragment-level date header to avoid duplicates.
        if (tvHabitDate != null) {
            tvHabitDate.setVisibility(View.GONE);
        }

        // Fetch Data
        fetchHabits();
    }

    private void fetchHabits() {
        showLoading(true);

        SharedPreferences preferences = getContext().getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String userId = preferences.getString("user_id", ""); 
        String userRole = preferences.getString("user_role", ""); 

        Log.d("HabitFragment", "Fetching habits. ID: " + userId + ", Role: " + userRole);

        if (userId.isEmpty()) {
             showLoading(false);
             Toast.makeText(getContext(), "Sesi tidak valid. Silakan login ulang.", Toast.LENGTH_SHORT).show();
             return;
        }

        // Validasi Client-Side: Hanya role 'siswa' yang boleh
        if (!"siswa".equalsIgnoreCase(userRole)) {
            showLoading(false);
            if (tvEmptyState != null) {
                tvEmptyState.setText("Fitur Habit hanya untuk Siswa.\nRole Anda: " + userRole);
                tvEmptyState.setVisibility(View.VISIBLE);
            }
            return;
        }

        ApiService apiService = ApiConfig.getApiService();
        Call<HabitResponse> call = apiService.getHabits(userId);
        
        call.enqueue(new Callback<HabitResponse>() {
            @Override
            public void onResponse(@NonNull Call<HabitResponse> call, @NonNull Response<HabitResponse> response) {
                showLoading(false);
                if (response.isSuccessful() && response.body() != null) {
                    HabitResponse habitResponse = response.body();
                    if ("success".equals(habitResponse.getStatus())) {
                        habitList.clear();
                        if (habitResponse.getData() != null) {
                            habitList.addAll(habitResponse.getData());
                            
                            if (!habitList.isEmpty()) {
                                String date = habitList.get(0).getTanggal();
                                if (tvHabitDate != null) tvHabitDate.setText(date);
                                if (tvEmptyState != null) tvEmptyState.setVisibility(View.GONE);
                            } else {
                                if (tvEmptyState != null) tvEmptyState.setVisibility(View.VISIBLE);
                            }
                        }
                        habitAdapter.notifyDataSetChanged();
                    } else {
                        Toast.makeText(getContext(), "Gagal: " + habitResponse.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                } else {
                    try {
                        String errorBody = response.errorBody() != null ? response.errorBody().string() : "";
                        Log.e("HabitFragment", "Error " + response.code() + ": " + errorBody);
                        
                        if (response.code() == 403) {
                            // Tampilkan detail error dari server jika ada
                            String msg = "Akses Ditolak (403).";
                            if (!errorBody.isEmpty() && !errorBody.startsWith("<")) {
                                // Jika JSON/Text, tampilkan
                                msg += " Info: " + errorBody;
                            } else {
                                // Jika HTML, tampilkan generik
                                msg += " Cek izin akun Anda.";
                            }
                            Toast.makeText(getContext(), msg, Toast.LENGTH_LONG).show();
                        } else {
                            Toast.makeText(getContext(), "Server Error: " + response.code(), Toast.LENGTH_SHORT).show();
                        }
                    } catch (IOException e) {
                        e.printStackTrace();
                    }
                }
            }

            @Override
            public void onFailure(@NonNull Call<HabitResponse> call, @NonNull Throwable t) {
                showLoading(false);
                Toast.makeText(getContext(), "Koneksi Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void showLoading(boolean isLoading) {
        if (pbLoading != null) {
            pbLoading.setVisibility(isLoading ? View.VISIBLE : View.GONE);
        }
    }
}
