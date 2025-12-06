package com.example.neoblue;

import android.app.AlertDialog;
import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Spinner;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;

import com.example.neoblue.adapter.JadwalAdapter;
import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.Jadwal;
import com.example.neoblue.models.JadwalResponse;
import com.example.neoblue.models.Subtest;
import com.example.neoblue.models.SubtestResponse;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class JadwalFragment extends Fragment {

    private Spinner spinnerHari, spinnerMapel, spinnerJamMulai, spinnerJamSelesai;
    private TextView btnTambahJadwal;
    private RecyclerView rvJadwal;
    private JadwalAdapter jadwalAdapter;
    private List<Jadwal> jadwalList;
    // Summary TextViews
    private TextView summarySenin, summarySelasa, summaryRabu, summaryKamis, summaryJumat, summarySabtu;
    
    // Data untuk Spinner
    private List<Subtest> subtestList = new ArrayList<>();
    private List<String> subtestNames = new ArrayList<>();
    private ArrayAdapter<String> subtestAdapter;
    
    private String userId;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        return inflater.inflate(R.layout.fragment_jadwal, container, false);
    }

    @Override
    public void onViewCreated(@NonNull View view, @Nullable Bundle savedInstanceState) {
        super.onViewCreated(view, savedInstanceState);

        // 1. Init Views
        spinnerHari = view.findViewById(R.id.spinner_hari);
        spinnerMapel = view.findViewById(R.id.spinner_mapel);
        spinnerJamMulai = view.findViewById(R.id.spinner_jam_mulai);
        spinnerJamSelesai = view.findViewById(R.id.spinner_jam_selesai);
        btnTambahJadwal = view.findViewById(R.id.btn_tambah_jadwal);
        rvJadwal = view.findViewById(R.id.rv_jadwal);

        // 2. Setup User ID
        SharedPreferences preferences = getContext().getSharedPreferences("user_session", Context.MODE_PRIVATE);
        userId = preferences.getString("user_id", "");

        // 3. Setup RecyclerView
        rvJadwal.setLayoutManager(new LinearLayoutManager(getContext()));
        jadwalList = new ArrayList<>();
        jadwalAdapter = new JadwalAdapter(getContext(), jadwalList);
        rvJadwal.setAdapter(jadwalAdapter);
        
        jadwalAdapter.setOnItemClickListener(position -> {
            Jadwal jadwal = jadwalList.get(position);
            confirmDeleteJadwal(jadwal.getId());
        });

        // 4. Setup Spinners
        setupSpinners();

        // 5. Load Data
        loadSubtests();

        // Init summary views (before loading jadwal so renderSummary can safely run)
        summarySenin = view.findViewById(R.id.summary_senin_text);
        summarySelasa = view.findViewById(R.id.summary_selasa_text);
        summaryRabu = view.findViewById(R.id.summary_rabu_text);
        summaryKamis = view.findViewById(R.id.summary_kamis_text);
        summaryJumat = view.findViewById(R.id.summary_jumat_text);
        summarySabtu = view.findViewById(R.id.summary_sabtu_text);

        loadJadwal();

        // 6. Listener Add
        btnTambahJadwal.setOnClickListener(v -> addJadwal());
    }

    private void setupSpinners() {
        // Hari
        String[] hari = {"Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"};
        ArrayAdapter<String> adapterHari = new ArrayAdapter<>(getContext(), android.R.layout.simple_spinner_item, hari);
        adapterHari.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerHari.setAdapter(adapterHari);

        // Jam (00:00 - 23:00)
        List<String> jam = new ArrayList<>();
        for (int i = 0; i < 24; i++) {
            jam.add(String.format("%02d:00", i));
            jam.add(String.format("%02d:30", i));
        }
        ArrayAdapter<String> adapterJam = new ArrayAdapter<>(getContext(), android.R.layout.simple_spinner_item, jam);
        adapterJam.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerJamMulai.setAdapter(adapterJam);
        spinnerJamSelesai.setAdapter(adapterJam);
        
        // Mapel (Placeholder, akan diisi dari API)
        subtestAdapter = new ArrayAdapter<>(getContext(), android.R.layout.simple_spinner_item, subtestNames);
        subtestAdapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
        spinnerMapel.setAdapter(subtestAdapter);
    }

    private void loadSubtests() {
        ApiService apiService = ApiConfig.getApiService();
        apiService.getSubtests().enqueue(new Callback<SubtestResponse>() {
            @Override
            public void onResponse(Call<SubtestResponse> call, Response<SubtestResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    SubtestResponse res = response.body();
                    if (res.getData() != null) {
                        subtestList.clear();
                        subtestNames.clear();
                        subtestList.addAll(res.getData());
                        for (Subtest s : subtestList) {
                            subtestNames.add(s.getNamaSubtest());
                        }
                        subtestAdapter.notifyDataSetChanged();
                    }
                }
            }

            @Override
            public void onFailure(Call<SubtestResponse> call, Throwable t) {
                Toast.makeText(getContext(), "Gagal memuat mapel", Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void loadJadwal() {
        if (userId.isEmpty()) return;

        ApiService apiService = ApiConfig.getApiService();
        // Perbaikan: Mengirim user_id sebagai parameter
        apiService.getJadwal(userId).enqueue(new Callback<JadwalResponse>() {
            @Override
            public void onResponse(Call<JadwalResponse> call, Response<JadwalResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    JadwalResponse res = response.body();
                    if ("success".equals(res.getStatus()) && res.getData() != null) {
                        jadwalList.clear();
                        jadwalList.addAll(res.getData());
                        jadwalAdapter.notifyDataSetChanged();
                        renderSummary(res.getData());
                    }
                } else {
                    if (response.code() == 401) {
                         try {
                            String errorBody = response.errorBody() != null ? response.errorBody().string() : "";
                            Toast.makeText(getContext(), "Gagal memuat jadwal: Unauthorized. Cek API Server.", Toast.LENGTH_LONG).show();
                        } catch (IOException e) {
                            e.printStackTrace();
                        }
                    }
                }
            }

            @Override
            public void onFailure(Call<JadwalResponse> call, Throwable t) {
                // Handle connection errors
            }
        });
    }

    // Render weekly summary into the summary TextViews
    private void renderSummary(List<Jadwal> schedules) {
        if (schedules == null) schedules = new ArrayList<>();

        Map<String, List<Jadwal>> byDay = new java.util.HashMap<>();
        for (Jadwal j : schedules) {
            String day = j.getHari();
            if (!byDay.containsKey(day)) byDay.put(day, new ArrayList<>());
            byDay.get(day).add(j);
        }

        summarySenin.setText(buildSummaryText(byDay.get("Senin")));
        summarySelasa.setText(buildSummaryText(byDay.get("Selasa")));
        summaryRabu.setText(buildSummaryText(byDay.get("Rabu")));
        summaryKamis.setText(buildSummaryText(byDay.get("Kamis")));
        summaryJumat.setText(buildSummaryText(byDay.get("Jumat")));
        summarySabtu.setText(buildSummaryText(byDay.get("Sabtu")));
    }

    private String buildSummaryText(List<Jadwal> list) {
        if (list == null || list.isEmpty()) return "Tidak ada jadwal";
        StringBuilder sb = new StringBuilder();
        for (int i = 0; i < list.size(); i++) {
            Jadwal j = list.get(i);
            String label = (j.getSingkatan() != null && !j.getSingkatan().trim().isEmpty()) ? j.getSingkatan() : j.getNamaSubtest();
            String start = j.getJamMulai() != null && j.getJamMulai().length() >= 5 ? j.getJamMulai().substring(0,5) : j.getJamMulai();
            String end = j.getJamSelesai() != null && j.getJamSelesai().length() >= 5 ? j.getJamSelesai().substring(0,5) : j.getJamSelesai();
            sb.append(label).append("\n").append(start).append(" - ").append(end);
            if (i < list.size() - 1) sb.append("\n\n");
        }
        return sb.toString();
    }

    private void addJadwal() {
        String hari = spinnerHari.getSelectedItem().toString();
        String jamMulai = spinnerJamMulai.getSelectedItem().toString();
        String jamSelesai = spinnerJamSelesai.getSelectedItem().toString();
        int posMapel = spinnerMapel.getSelectedItemPosition();
        
        if (posMapel < 0 || posMapel >= subtestList.size()) {
            Toast.makeText(getContext(), "Pilih mata pelajaran", Toast.LENGTH_SHORT).show();
            return;
        }
        
        // Validasi User ID
        if (userId == null || userId.isEmpty()) {
            Toast.makeText(getContext(), "User ID tidak ditemukan. Login ulang.", Toast.LENGTH_SHORT).show();
            return;
        }
        
        int subtestId = Integer.parseInt(subtestList.get(posMapel).getId());

        // Validasi Jam
        if (jamMulai.compareTo(jamSelesai) >= 0) {
            Toast.makeText(getContext(), "Jam mulai harus lebih awal dari jam selesai", Toast.LENGTH_SHORT).show();
            return;
        }

        Map<String, Object> params = new HashMap<>();
        params.put("hari", hari);
        params.put("subtest_id", subtestId);
        params.put("jam_mulai", jamMulai);
        params.put("jam_selesai", jamSelesai);
        params.put("user_id", userId); // Pastikan user_id dikirim sebagai String/Int yang valid

        ApiService apiService = ApiConfig.getApiService();
        apiService.addJadwal(params).enqueue(new Callback<JadwalResponse>() {
            @Override
            public void onResponse(Call<JadwalResponse> call, Response<JadwalResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    JadwalResponse res = response.body();
                    if ("success".equals(res.getStatus())) {
                         Toast.makeText(getContext(), "Jadwal berhasil ditambahkan", Toast.LENGTH_SHORT).show();
                         loadJadwal(); // Refresh list
                    } else {
                         Toast.makeText(getContext(), "Gagal: " + res.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                } else {
                    try {
                        String errorBody = response.errorBody() != null ? response.errorBody().string() : "";
                        // Menampilkan pesan error dari server (misal: Unauthorized)
                        Toast.makeText(getContext(), "Gagal tambah: " + response.message() + " (" + errorBody + ")", Toast.LENGTH_LONG).show();
                    } catch (IOException e) {
                        e.printStackTrace();
                    }
                }
            }

            @Override
            public void onFailure(Call<JadwalResponse> call, Throwable t) {
                Toast.makeText(getContext(), "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private void confirmDeleteJadwal(int id) {
        new AlertDialog.Builder(getContext())
                .setTitle("Hapus Jadwal")
                .setMessage("Yakin ingin menghapus jadwal ini?")
                .setPositiveButton("Ya", (dialog, which) -> deleteJadwal(id))
                .setNegativeButton("Batal", null)
                .show();
    }

    private void deleteJadwal(int id) {
        // Validasi User ID
        if (userId == null || userId.isEmpty()) {
            Toast.makeText(getContext(), "User ID tidak ditemukan. Login ulang.", Toast.LENGTH_SHORT).show();
            return;
        }

        Map<String, Object> params = new HashMap<>();
        params.put("id", id);
        params.put("user_id", userId); // Kirim user_id

        ApiService apiService = ApiConfig.getApiService();
        apiService.deleteJadwal(params).enqueue(new Callback<JadwalResponse>() {
            @Override
            public void onResponse(Call<JadwalResponse> call, Response<JadwalResponse> response) {
                if (response.isSuccessful()) {
                    Toast.makeText(getContext(), "Jadwal dihapus", Toast.LENGTH_SHORT).show();
                    loadJadwal();
                } else {
                    try {
                        String errorBody = response.errorBody() != null ? response.errorBody().string() : "";
                        Toast.makeText(getContext(), "Gagal hapus: " + response.message() + " " + errorBody, Toast.LENGTH_LONG).show();
                    } catch (IOException e) {
                        e.printStackTrace();
                    }
                }
            }

            @Override
            public void onFailure(Call<JadwalResponse> call, Throwable t) {
                Toast.makeText(getContext(), "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}
