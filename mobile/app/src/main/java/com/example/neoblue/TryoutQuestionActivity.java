package com.example.neoblue;

import android.os.Bundle;
import android.os.CountDownTimer;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

public class TryoutQuestionActivity extends AppCompatActivity {

    private TextView tvTimer;
    private CountDownTimer countDownTimer;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_soal_tryout);

        tvTimer = findViewById(R.id.tv_timer);

        // 1. Jalankan Timer 40 menit (dalam milidetik)
        startTimer(40 * 60 * 1000);

        // 2. Logic Klik Jawaban (Contoh untuk A dan B)
        LinearLayout optionA = findViewById(R.id.option_a);
        LinearLayout optionB = findViewById(R.id.option_b);

        if (optionA != null) {
            optionA.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Toast.makeText(TryoutQuestionActivity.this, "Jawaban A dipilih", Toast.LENGTH_SHORT).show();
                    // Nanti tambahkan logika ubah warna background jadi biru jika dipilih
                }
            });
        }
        
        if (optionB != null) {
            optionB.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Toast.makeText(TryoutQuestionActivity.this, "Jawaban B dipilih", Toast.LENGTH_SHORT).show();
                }
            });
        }

        // 3. Tombol Selesai
        TextView btnSelesai = findViewById(R.id.btn_selesai_ujian);
        if (btnSelesai != null) {
            btnSelesai.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Toast.makeText(TryoutQuestionActivity.this, "Ujian Selesai!", Toast.LENGTH_SHORT).show();
                    finish(); // Kembali ke halaman sebelumnya
                }
            });
        }
    }

    private void startTimer(long durationInMillis) {
        countDownTimer = new CountDownTimer(durationInMillis, 1000) {
            @Override
            public void onTick(long millisUntilFinished) {
                // Update text timer setiap detik
                int minutes = (int) (millisUntilFinished / 1000) / 60;
                int seconds = (int) (millisUntilFinished / 1000) % 60;

                String timeLeftFormatted = String.format("%02d:%02d", minutes, seconds);
                if (tvTimer != null) tvTimer.setText(timeLeftFormatted);
            }

            @Override
            public void onFinish() {
                if (tvTimer != null) tvTimer.setText("00:00");
                Toast.makeText(TryoutQuestionActivity.this, "Waktu Habis!", Toast.LENGTH_LONG).show();
            }
        }.start();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        // Hentikan timer jika keluar halaman agar tidak memory leak
        if (countDownTimer != null) {
            countDownTimer.cancel();
        }
    }
}