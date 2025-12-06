package com.example.neoblue.adapter;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.neoblue.R;
import com.example.neoblue.SoalTryoutActivity;
import com.example.neoblue.models.MateriTryoutResponse;

import java.util.List;

public class SubtestAdapter extends RecyclerView.Adapter<SubtestAdapter.ViewHolder> {

    private List<MateriTryoutResponse.Materi> materiList;
    private Context context;
    private String tryoutId;

    public SubtestAdapter(Context context, List<MateriTryoutResponse.Materi> list, String tryoutId) {
        this.context = context;
        this.materiList = list;
        this.tryoutId = tryoutId;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_detail_tryout, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        MateriTryoutResponse.Materi materi = materiList.get(position);
        
        holder.tvNama.setText(materi.getNamaMapel());
        
        if (materi.getStatus() == 1) {
            // Sudah Dikerjakan
            String nilai = materi.getNilai();
            if (nilai == null || nilai.equals("null")) nilai = "0.00";
            
            holder.tvDetail.setText("Nilai: " + nilai);
            
            holder.btnKerjakan.setText("Lihat Hasil");
            holder.btnKerjakan.setBackgroundTintList(context.getResources().getColorStateList(R.color.neo_yellow));
            holder.btnKerjakan.setTextColor(context.getResources().getColor(R.color.black));
            
            holder.btnKerjakan.setOnClickListener(v -> {
                // Tampilkan dialog hasil tryout menggunakan layout dialog_hasil_tryout.xml
                try {
                    android.view.View dialogView = LayoutInflater.from(context).inflate(R.layout.dialog_hasil_tryout, null);

                    android.widget.TextView tvSkor = dialogView.findViewById(R.id.tv_skor_akhir);
                    android.widget.TextView tvBenar = dialogView.findViewById(R.id.tv_benar);
                    android.widget.TextView tvSalah = dialogView.findViewById(R.id.tv_salah);
                    android.widget.TextView tvKosong = dialogView.findViewById(R.id.tv_kosong);
                    android.widget.TextView tvJudul = dialogView.findViewById(R.id.tv_judul_hasil);

                    String nilaiDialog = materi.getNilai();
                    if (nilaiDialog == null || nilaiDialog.equals("null")) nilaiDialog = "0";
                    tvSkor.setText(nilaiDialog.replaceAll("\\.0+$", ""));

                    // Gunakan nilai benar/salah/kosong dari server jika tersedia
                    Integer correct = materi.getCorrectCount();
                    Integer incorrect = materi.getIncorrectCount();
                    Integer unanswered = materi.getUnansweredCount();
                    Integer totalQ = materi.getTotalQuestions();

                    if (totalQ == null || totalQ == 0) {
                        // fallback ke jumlah soal yang dilaporkan
                        try {
                            totalQ = Integer.parseInt(materi.getJumlahSoal());
                        } catch (Exception ignored) { totalQ = 0; }
                    }

                    if (correct != null || incorrect != null || unanswered != null) {
                        tvBenar.setText(correct != null ? String.valueOf(correct) : "-");
                        tvSalah.setText(incorrect != null ? String.valueOf(incorrect) : "-");
                        tvKosong.setText(unanswered != null ? String.valueOf(unanswered) : "-");
                    } else if (totalQ != null && totalQ > 0) {
                        // Jika server tidak memberi rincian, estimasi berdasarkan skor persen
                        int benar = 0, salah = 0, kosong = 0;
                        try {
                            double perc = Double.parseDouble(nilaiDialog);
                            if (perc > 1) {
                                benar = (int) Math.round(perc / 100.0 * totalQ);
                                salah = totalQ - benar;
                                if (salah < 0) salah = 0;
                            }
                        } catch (Exception ignored) {}
                        tvBenar.setText(String.valueOf(benar));
                        tvSalah.setText(String.valueOf(salah));
                        tvKosong.setText(String.valueOf(kosong));
                    } else {
                        tvBenar.setText("-");
                        tvSalah.setText("-");
                        tvKosong.setText("-");
                    }

                    androidx.appcompat.app.AlertDialog.Builder builder = new androidx.appcompat.app.AlertDialog.Builder(context);
                    builder.setView(dialogView);
                    androidx.appcompat.app.AlertDialog alert = builder.create();

                    // Tombol kembali pada dialog
                    android.widget.Button btnKembali = dialogView.findViewById(R.id.btn_kembali_daftar);
                    btnKembali.setOnClickListener(x -> alert.dismiss());

                    alert.show();

                } catch (Exception e) {
                    e.printStackTrace();
                }
            });
        } else {
            // Belum Dikerjakan
            holder.tvDetail.setText(materi.getJumlahSoal() + " soal · " + materi.getDurasi() + " menit");
            
            holder.btnKerjakan.setText("Kerjakan");
            holder.btnKerjakan.setBackgroundTintList(context.getResources().getColorStateList(R.color.neo_blue));
            holder.btnKerjakan.setTextColor(Color.WHITE);
            
            holder.btnKerjakan.setOnClickListener(v -> {
                Intent intent = new Intent(context, SoalTryoutActivity.class);
                intent.putExtra("MATERI_ID", materi.getId()); // This is subtest_id
                intent.putExtra("TRYOUT_ID", tryoutId);
                intent.putExtra("JUDUL", materi.getNamaMapel());
                context.startActivity(intent);
            });
        }
    }

    @Override
    public int getItemCount() {
        return materiList.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvNama, tvDetail;
        Button btnKerjakan;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvNama = itemView.findViewById(R.id.tv_nama_subtes);
            tvDetail = itemView.findViewById(R.id.tv_detail_subtes);
            btnKerjakan = itemView.findViewById(R.id.btn_kerjakan_soal);
        }
    }
}
