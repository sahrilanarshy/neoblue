package com.example.neoblue;

import android.app.AlertDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.os.CountDownTimer;
import android.util.Log;
import android.view.LayoutInflater;
import android.text.Editable;
import android.text.InputType;
import android.text.TextWatcher;
import android.widget.EditText;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.content.ContextCompat;
import androidx.core.view.WindowCompat;

import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.SoalTryoutResponse;
import com.example.neoblue.models.SubmitTryoutRequest;
import com.example.neoblue.models.SubmitTryoutResponse;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class SoalTryoutActivity extends AppCompatActivity {

    private TextView tvTimer, tvMapelName, tvPertanyaan;
    private TextView tvOptionA, tvOptionB, tvOptionC, tvOptionD, tvOptionE;
    private LinearLayout layoutOptionA, layoutOptionB, layoutOptionC, layoutOptionD, layoutOptionE;
    private LinearLayout layoutSoalContainer;
    private ProgressBar pbLoadingSoal;
    private Button btnPrev, btnNext; 
    private TextView btnSelesai; 
    private LinearLayout layoutNomorSoal;
    
    private CountDownTimer countDownTimer;
    private List<SoalTryoutResponse.Question> questions = new ArrayList<>();
    private int currentQuestionIndex = 0;
    
    private Map<String, String> userAnswers = new HashMap<>();

    private String materiId;
    private String tryoutId;
    private String judulMapel;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        try {
            WindowCompat.setDecorFitsSystemWindows(getWindow(), false);
            setContentView(R.layout.activity_soal_tryout);
            
            setupHeader();

            materiId = getIntent().getStringExtra("MATERI_ID");
            tryoutId = getIntent().getStringExtra("TRYOUT_ID");
            judulMapel = getIntent().getStringExtra("JUDUL");

            initializeViews();
            setupListeners();
            
            if (judulMapel != null) {
                tvMapelName.setText(judulMapel);
            }
            
            loadSoal();
        } catch (Exception e) {
            e.printStackTrace();
            Toast.makeText(this, "Terjadi Crash: " + e.getMessage(), Toast.LENGTH_LONG).show();
        }
    }
    
    @Override
    public void onBackPressed() {
        new AlertDialog.Builder(this)
            .setTitle("Keluar Ujian?")
            .setMessage("Waktu akan terus berjalan jika Anda keluar. Yakin?")
            .setPositiveButton("Ya", (dialog, which) -> super.onBackPressed())
            .setNegativeButton("Tidak", null)
            .show();
    }

    private void setupHeader() {
        // Note: Layout header is included in activity_soal_tryout.xml
        // It has btn_notifikasi and btn_profil
        View btnNotif = findViewById(R.id.btn_notifikasi);
        if (btnNotif != null) {
            btnNotif.setOnClickListener(v -> startActivity(new Intent(SoalTryoutActivity.this, NotifikasiActivity.class)));
        }
        
        View btnProfil = findViewById(R.id.btn_profil);
        if (btnProfil != null) {
            btnProfil.setOnClickListener(v -> startActivity(new Intent(SoalTryoutActivity.this, ProfilActivity.class)));
        }
        // header view is present in layout; keep behavior unchanged (no dynamic inset applied)
    }

    private void initializeViews() {
        tvTimer = findViewById(R.id.tv_timer);
        tvMapelName = findViewById(R.id.tv_mapel_name);
        tvPertanyaan = findViewById(R.id.tv_pertanyaan);
        
        tvOptionA = findViewById(R.id.tv_option_a);
        tvOptionB = findViewById(R.id.tv_option_b);
        tvOptionC = findViewById(R.id.tv_option_c);
        tvOptionD = findViewById(R.id.tv_option_d);
        tvOptionE = findViewById(R.id.tv_option_e);

        layoutOptionA = findViewById(R.id.option_a);
        layoutOptionB = findViewById(R.id.option_b);
        layoutOptionC = findViewById(R.id.option_c);
        layoutOptionD = findViewById(R.id.option_d);
        layoutOptionE = findViewById(R.id.option_e);
        
        layoutSoalContainer = findViewById(R.id.layout_soal_container);
        pbLoadingSoal = findViewById(R.id.pb_loading_soal);
        layoutNomorSoal = findViewById(R.id.layout_nomor_soal);

        btnPrev = findViewById(R.id.btn_prev_soal);
        btnNext = findViewById(R.id.btn_next_soal);
        btnSelesai = findViewById(R.id.btn_selesai_ujian);
        
        setupBottomNav();
    }
    
    private void setupBottomNav() {
        BottomNavigationView bottomNav = findViewById(R.id.bottom_navigation_tryout);
        if (bottomNav != null) {
            bottomNav.setSelectedItemId(R.id.nav_tryout);
            bottomNav.setOnNavigationItemSelectedListener(item -> {
                int itemId = item.getItemId();
                
                // Jika user sedang ujian, konfirmasi dulu sebelum pindah
                if (itemId != R.id.nav_tryout) {
                    confirmExit(itemId);
                    return false; 
                }
                return true;
            });
        }
    }
    
    private void confirmExit(int targetItemId) {
        new android.app.AlertDialog.Builder(this)
            .setTitle("Keluar Ujian?")
            .setMessage("Waktu akan terus berjalan jika Anda keluar. Yakin?")
            .setPositiveButton("Ya", (dialog, which) -> {
                Intent intent = new Intent(SoalTryoutActivity.this, MainActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);
                
                if (targetItemId == R.id.nav_materi) {
                    // Go home
                } else if (targetItemId == R.id.nav_habit) {
                    intent.putExtra("fragment", "habit");
                } else if (targetItemId == R.id.nav_short) {
                    intent.putExtra("fragment", "short");
                } else if (targetItemId == R.id.nav_jadwal) {
                    intent.putExtra("fragment", "jadwal");
                }
                
                startActivity(intent);
                finish();
            })
            .setNegativeButton("Tidak", null)
            .show();
    }

    private void setupListeners() {
        if (layoutOptionA != null) layoutOptionA.setOnClickListener(v -> selectAnswer("A"));
        if (layoutOptionB != null) layoutOptionB.setOnClickListener(v -> selectAnswer("B"));
        if (layoutOptionC != null) layoutOptionC.setOnClickListener(v -> selectAnswer("C"));
        if (layoutOptionD != null) layoutOptionD.setOnClickListener(v -> selectAnswer("D"));
        if (layoutOptionE != null) layoutOptionE.setOnClickListener(v -> selectAnswer("E"));

        if (btnPrev != null) {
            btnPrev.setOnClickListener(v -> {
                if (currentQuestionIndex > 0) {
                    currentQuestionIndex--;
                    displayQuestion();
                }
            });
        }

        if (btnNext != null) {
            btnNext.setOnClickListener(v -> {
                if (currentQuestionIndex < questions.size() - 1) {
                    currentQuestionIndex++;
                    displayQuestion();
                }
            });
        }

        if (btnSelesai != null) btnSelesai.setOnClickListener(v -> showFinishConfirmDialog());
    }

    private void showFinishConfirmDialog() {
        androidx.appcompat.app.AlertDialog.Builder builder = new androidx.appcompat.app.AlertDialog.Builder(this);
        builder.setTitle("Konfirmasi Selesai");
        builder.setMessage("Apakah Anda yakin ingin menyelesaikan tryout ini? Setelah selesai, Anda tidak dapat mengubah jawaban. Untuk mencegah klik tidak sengaja, ketik 'YA' sebagai konfirmasi.");

        // Buat input teks untuk konfirmasi "YA"
        final EditText input = new EditText(this);
        input.setHint("Ketik YA untuk konfirmasi");
        input.setInputType(InputType.TYPE_CLASS_TEXT);

        // Bungkus input dengan padding supaya tampil rapi
        LinearLayout container = new LinearLayout(this);
        container.setPadding(32, 16, 32, 4);
        container.addView(input);

        builder.setView(container);

        builder.setPositiveButton("Selesaikan", (dialog, which) -> {
            // final check (pada kasus tombol di-enable) lalu panggil finish
            if (input.getText() != null && "YA".equalsIgnoreCase(input.getText().toString().trim())) {
                finishExam();
            }
        });

        builder.setNegativeButton("Batal", null);

        final androidx.appcompat.app.AlertDialog dialog = builder.create();
        dialog.setOnShowListener(dlg -> {
            final Button positive = dialog.getButton(androidx.appcompat.app.AlertDialog.BUTTON_POSITIVE);
            if (positive != null) positive.setEnabled(false);

            input.addTextChangedListener(new TextWatcher() {
                @Override
                public void beforeTextChanged(CharSequence s, int start, int count, int after) {}

                @Override
                public void onTextChanged(CharSequence s, int start, int before, int count) {
                    if (positive != null) {
                        String val = s == null ? "" : s.toString().trim();
                        positive.setEnabled("YA".equalsIgnoreCase(val));
                    }
                }

                @Override
                public void afterTextChanged(Editable s) {}
            });
        });

        dialog.show();
    }

    private void loadSoal() {
        Log.d("SoalTryout", "loadSoal() called with tryoutId=" + tryoutId + " materiId=" + materiId);

        // Basic validation to prevent calling API with invalid IDs
        if (tryoutId == null || tryoutId.isEmpty() || materiId == null || materiId.isEmpty()) {
            Log.e("SoalTryout", "Invalid tryoutId or materiId: tryoutId=" + tryoutId + " materiId=" + materiId);
            showError("ID Tryout atau Subtest tidak valid (missing). Cek parameter.");
            return;
        }
        if (pbLoadingSoal != null) pbLoadingSoal.setVisibility(View.VISIBLE);
        if (layoutSoalContainer != null) layoutSoalContainer.setVisibility(View.GONE);

        ApiService apiService = ApiConfig.getApiService();
        Call<SoalTryoutResponse> call = apiService.getSoalTryout(tryoutId, materiId);

        call.enqueue(new Callback<SoalTryoutResponse>() {
            @Override
            public void onResponse(Call<SoalTryoutResponse> call, Response<SoalTryoutResponse> response) {
                if (pbLoadingSoal != null) pbLoadingSoal.setVisibility(View.GONE);
                
                if (response.isSuccessful() && response.body() != null) {
                    SoalTryoutResponse data = response.body();
                    if ("success".equals(data.getStatus())) {
                        questions = data.getQuestions();
                        
                        if (questions != null && !questions.isEmpty()) {
                            if (layoutSoalContainer != null) layoutSoalContainer.setVisibility(View.VISIBLE);
                            
                                // Inisialisasi jawaban user (null berarti belum dijawab)
                                userAnswers.clear();
                                for (SoalTryoutResponse.Question q : questions) {
                                    userAnswers.put(q.getId(), null);
                                }

                                // Perbarui visibilitas tombol Selesai (hanya tampil jika semua terjawab)
                                updateFinishButtonVisibility();

                            int durationMinutes = data.getWaktuPengerjaan();
                            if (durationMinutes > 0) {
                                startTimer(durationMinutes * 60 * 1000L);
                            } else {
                                if (tvTimer != null) tvTimer.setText("No Timer");
                            }
                            
                            setupNomorSoal();
                            displayQuestion();
                        } else {
                            showError("Soal tidak tersedia (List kosong).");
                        }
                    } else {
                         showError("Status error dari server: " + data.getStatus());
                    }
                } else {
                     showError("Error server: " + response.code());
                }
            }

            @Override
            public void onFailure(Call<SoalTryoutResponse> call, Throwable t) {
                if (pbLoadingSoal != null) pbLoadingSoal.setVisibility(View.GONE);
                showError("Koneksi error: " + t.getMessage());
            }
        });
    }
    
    private void showError(String message) {
        if (layoutSoalContainer != null) layoutSoalContainer.setVisibility(View.VISIBLE);
        if (tvPertanyaan != null) tvPertanyaan.setText(message);
        Toast.makeText(SoalTryoutActivity.this, message, Toast.LENGTH_LONG).show();
    }

    private void setupNomorSoal() {
        if (layoutNomorSoal == null) return;
        layoutNomorSoal.removeAllViews();
        for (int i = 0; i < questions.size(); i++) {
            TextView tvNomor = new TextView(this);
            tvNomor.setText(String.valueOf(i + 1));
            tvNomor.setTextColor(getResources().getColor(R.color.text_grey));
            tvNomor.setBackgroundResource(R.drawable.circle_grey_filled);
            tvNomor.setGravity(android.view.Gravity.CENTER);
            
            LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(100, 100); 
            params.setMargins(0, 0, 16, 0);
            tvNomor.setLayoutParams(params);
            
            final int index = i;
            tvNomor.setOnClickListener(v -> {
                currentQuestionIndex = index;
                displayQuestion();
            });
            
            layoutNomorSoal.addView(tvNomor);
        }
    }

    private void displayQuestion() {
        if (questions.isEmpty()) return;

        SoalTryoutResponse.Question q = questions.get(currentQuestionIndex);

        if (tvPertanyaan != null) {
            String konteks = q.getKonteksSoal() != null ? q.getKonteksSoal() + "\n\n" : "";
            tvPertanyaan.setText(konteks + q.getPertanyaan());
        }
        
        if (tvOptionA != null) tvOptionA.setText(q.getPilihanA());
        if (tvOptionB != null) tvOptionB.setText(q.getPilihanB());
        if (tvOptionC != null) tvOptionC.setText(q.getPilihanC());
        if (tvOptionD != null) tvOptionD.setText(q.getPilihanD());
        if (tvOptionE != null) tvOptionE.setText(q.getPilihanE());

        resetOptionStyles();

        String currentAnswer = userAnswers.get(q.getId());
        if (currentAnswer != null) {
            highlightAnswer(currentAnswer);
        }
        
        if (btnPrev != null) {
            btnPrev.setEnabled(currentQuestionIndex > 0);
            btnPrev.setAlpha(currentQuestionIndex > 0 ? 1.0f : 0.5f);
        }
        
        if (btnNext != null) {
            btnNext.setText(currentQuestionIndex == questions.size() - 1 ? "Selesai" : "Selanjutnya ▶");
        }
        
        updateNomorSoalUI();
        // Pastikan tombol selesai diperbarui setiap kali pertanyaan berubah
        updateFinishButtonVisibility();
    }

    private void updateFinishButtonVisibility() {
        if (btnSelesai == null) return;

        boolean allAnswered = true;
        if (questions == null || questions.isEmpty()) allAnswered = false;
        else {
            for (SoalTryoutResponse.Question q : questions) {
                String ans = userAnswers.get(q.getId());
                if (ans == null) { allAnswered = false; break; }
            }
        }

        btnSelesai.setVisibility(allAnswered ? View.VISIBLE : View.GONE);
    }

    private void updateNomorSoalUI() {
        if (layoutNomorSoal == null) return;
        for (int i = 0; i < layoutNomorSoal.getChildCount(); i++) {
            TextView tv = (TextView) layoutNomorSoal.getChildAt(i);
            SoalTryoutResponse.Question q = questions.get(i);
            
            if (i == currentQuestionIndex) {
                tv.setBackgroundResource(R.drawable.circle_blue_filled);
                tv.setTextColor(ContextCompat.getColor(this, R.color.white));
            } else if (userAnswers.containsKey(q.getId())) {
                tv.setBackgroundResource(R.drawable.bg_circle_blue_light);
                tv.setTextColor(ContextCompat.getColor(this, R.color.neo_blue));
            } else {
                tv.setBackgroundResource(R.drawable.circle_grey_filled);
                tv.setTextColor(ContextCompat.getColor(this, R.color.text_grey));
            }
        }
    }

    private void selectAnswer(String answer) {
        SoalTryoutResponse.Question q = questions.get(currentQuestionIndex);
        userAnswers.put(q.getId(), answer);
        
        resetOptionStyles();
        highlightAnswer(answer);
        updateNomorSoalUI();
        updateFinishButtonVisibility();
    }

    private void resetOptionStyles() {
        if (layoutOptionA != null) layoutOptionA.setBackgroundResource(R.drawable.bg_option_card);
        if (layoutOptionB != null) layoutOptionB.setBackgroundResource(R.drawable.bg_option_card);
        if (layoutOptionC != null) layoutOptionC.setBackgroundResource(R.drawable.bg_option_card);
        if (layoutOptionD != null) layoutOptionD.setBackgroundResource(R.drawable.bg_option_card);
        if (layoutOptionE != null) layoutOptionE.setBackgroundResource(R.drawable.bg_option_card);
    }

    private void highlightAnswer(String answer) {
        int selectedBg = R.drawable.bg_rounded_blue_light;
        
        if (layoutOptionA != null && "A".equals(answer)) layoutOptionA.setBackgroundResource(selectedBg);
        if (layoutOptionB != null && "B".equals(answer)) layoutOptionB.setBackgroundResource(selectedBg);
        if (layoutOptionC != null && "C".equals(answer)) layoutOptionC.setBackgroundResource(selectedBg);
        if (layoutOptionD != null && "D".equals(answer)) layoutOptionD.setBackgroundResource(selectedBg);
        if (layoutOptionE != null && "E".equals(answer)) layoutOptionE.setBackgroundResource(selectedBg);
    }

    private void finishExam() {
        // Calculate Score Locally for UI
        int correct = 0;
        int wrong = 0;
        int empty = 0;

        // Prepare answers for server submission
        List<String> answerList = new ArrayList<>();

        for (SoalTryoutResponse.Question q : questions) {
            String userAnswer = userAnswers.get(q.getId());

            // Add to list for server (indexed array)
            answerList.add(userAnswer);

            String correctAnswer = q.getKunciJawaban();

            if (userAnswer == null) {
                empty++;
            } else if (correctAnswer != null && userAnswer.equalsIgnoreCase(correctAnswer)) {
                correct++;
            } else {
                wrong++;
            }
        }

        int totalQuestions = questions.size();
        int score = 0;
        if (totalQuestions > 0) {
            score = (int) Math.round((correct * 100.0) / totalQuestions);
        }

        // Show Dialog Immediately (local result dialog)
        showResultDialog(correct, wrong, empty, score);

        // Send to Server in Background (no extra modal)
        submitToServer(answerList);
    }
    
    private void submitToServer(List<String> answerList) {
        // Get User ID from Shared Preferences
        SharedPreferences sharedPreferences = getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String userId = sharedPreferences.getString("user_id", null);
        
        if (userId == null) {
            Toast.makeText(this, "Gagal mengirim data: User ID tidak ditemukan", Toast.LENGTH_SHORT).show();
            return;
        }
        
        SubmitTryoutRequest request = new SubmitTryoutRequest(userId, tryoutId, materiId, answerList);
        ApiService apiService = ApiConfig.getApiService();
        Call<SubmitTryoutResponse> call = apiService.submitTryout(request);
        
        call.enqueue(new Callback<SubmitTryoutResponse>() {
            @Override
            public void onResponse(Call<SubmitTryoutResponse> call, Response<SubmitTryoutResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    if ("success".equals(response.body().getStatus())) {
                        Log.d("TryoutSubmit", "Data berhasil disimpan. Session ID: " + response.body().getSessionId());
                        // nothing more here (used by caller)
                    } else {
                        Log.e("TryoutSubmit", "Gagal simpan: " + response.body().getMessage());
                    }
                } else {
                    Log.e("TryoutSubmit", "Error API: " + response.code());
                }
            }

            @Override
            public void onFailure(Call<SubmitTryoutResponse> call, Throwable t) {
                Log.e("TryoutSubmit", "Koneksi error: " + t.getMessage());
            }
        });
    }

    
    
    private void showResultDialog(int correct, int wrong, int empty, int score) {
        AlertDialog.Builder builder = new AlertDialog.Builder(this);
        View view = LayoutInflater.from(this).inflate(R.layout.dialog_hasil_tryout, null);
        
        TextView tvSkor = view.findViewById(R.id.tv_skor_akhir);
        TextView tvBenar = view.findViewById(R.id.tv_benar);
        TextView tvSalah = view.findViewById(R.id.tv_salah);
        TextView tvKosong = view.findViewById(R.id.tv_kosong);
        TextView tvJudul = view.findViewById(R.id.tv_judul_hasil);
        Button btnKembali = view.findViewById(R.id.btn_kembali_daftar);
        
        if (judulMapel != null) {
            tvJudul.setText("Tryout Selesai! (" + judulMapel + ")");
        }
        
        tvSkor.setText(String.valueOf(score));
        tvSkor.setVisibility(View.VISIBLE);
        tvSkor.setTextColor(ContextCompat.getColor(this, R.color.neo_blue));
        Log.d("SoalTryout", "Showing result dialog with score=" + score + " (correct=" + correct + ")");
        tvBenar.setText(String.valueOf(correct));
        tvSalah.setText(String.valueOf(wrong));
        tvKosong.setText(String.valueOf(empty));
        
        builder.setView(view);
        builder.setCancelable(false);

        AlertDialog dialog = builder.create();
        if (dialog.getWindow() != null) {
            dialog.getWindow().setBackgroundDrawableResource(android.R.color.transparent);
        }

        // Ensure score text is applied when dialog is shown (defensive for rendering issues)
        dialog.setOnShowListener(di -> {
            try {
                TextView tv = view.findViewById(R.id.tv_skor_akhir);
                if (tv != null) {
                    tv.setText(String.valueOf(score));
                    tv.setVisibility(View.VISIBLE);
                    tv.setTextColor(ContextCompat.getColor(SoalTryoutActivity.this, R.color.neo_blue));
                }
            } catch (Exception ex) {
                Log.e("SoalTryout", "Failed to set score on dialog show: " + ex.getMessage());
            }
        });

        btnKembali.setOnClickListener(v -> {
            dialog.dismiss();
            finish(); // Keluar dari halaman soal
        });

        dialog.show();
    }

    private void startTimer(long durationInMillis) {
        if (countDownTimer != null) countDownTimer.cancel();
        
        countDownTimer = new CountDownTimer(durationInMillis, 1000) {
            @Override
            public void onTick(long millisUntilFinished) {
                int minutes = (int) (millisUntilFinished / 1000) / 60;
                int seconds = (int) (millisUntilFinished / 1000) % 60;
                String time = String.format("%02d:%02d", minutes, seconds);
                if (tvTimer != null) tvTimer.setText(time);
                
                if (millisUntilFinished < 30000 && tvTimer != null) {
                    tvTimer.setTextColor(ContextCompat.getColor(SoalTryoutActivity.this, android.R.color.holo_red_dark));
                }
            }

            @Override
            public void onFinish() {
                if (tvTimer != null) tvTimer.setText("00:00");
                finishExam(); // Otomatis hitung nilai saat waktu habis
            }
        }.start();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        if (countDownTimer != null) countDownTimer.cancel();
    }
}