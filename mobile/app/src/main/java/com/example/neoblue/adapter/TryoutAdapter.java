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

import com.example.neoblue.DaftarSubtestActivity;
import com.example.neoblue.R;
import com.example.neoblue.UpgradePremiumActivity;
import com.example.neoblue.models.TryoutResponse;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.List;
import java.util.Locale;

public class TryoutAdapter extends RecyclerView.Adapter<TryoutAdapter.ViewHolder> {

    private List<TryoutResponse.Tryout> tryoutList;
    private Context context;
    private boolean isPremiumUser;

    public TryoutAdapter(Context context, List<TryoutResponse.Tryout> list, boolean isPremiumUser) {
        this.context = context;
        this.tryoutList = list;
        this.isPremiumUser = isPremiumUser;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_tryout, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        TryoutResponse.Tryout tryout = tryoutList.get(position);
        
        holder.tvTitle.setText(tryout.getNamaTryout());
        holder.tvSubtitle.setText("Mulai Tanggal " + tryout.getTanggalMulai());
        
        String tipe = tryout.getTipe();
        boolean isTryoutGratis = "Gratis".equalsIgnoreCase(tipe) || "Free".equalsIgnoreCase(tipe);
        
        // Cek Tanggal untuk status "Segera Dimulai"
        boolean isUpcoming = false;
        boolean isExpired = false;
        try {
            // Asumsi format tanggal dari API yyyy-MM-dd
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd", Locale.getDefault());
            Date dateMulai = sdf.parse(tryout.getTanggalMulai());
            Date dateSelesai = sdf.parse(tryout.getTanggalSelesai());
            Date today = sdf.parse(sdf.format(new Date()));
            if (dateMulai != null && dateMulai.after(today)) {
                isUpcoming = true;
            }
            if (dateSelesai != null && dateSelesai.before(today)) {
                isExpired = true;
            }
        } catch (ParseException e) {
            e.printStackTrace();
        }

        holder.btnKerjakan.setVisibility(View.VISIBLE);
        holder.btnKerjakan.setEnabled(true);
        holder.btnKerjakan.setTextColor(Color.WHITE); // Reset text color

        if (isExpired) {
            holder.btnKerjakan.setText("Telah Berakhir");
            holder.btnKerjakan.setBackgroundColor(Color.parseColor("#F44336"));
            holder.btnKerjakan.setEnabled(false);
        } else if (isUpcoming) {
            // Status: Segera Dimulai (Belum tanggalnya)
            holder.btnKerjakan.setText("Segera Dimulai");
            holder.btnKerjakan.setBackgroundColor(Color.parseColor("#9E9E9E")); // Abu-abu
            holder.btnKerjakan.setEnabled(false); // Tidak bisa diklik
        } else {
            // Status: Bisa dikerjakan atau perlu akses
            if (isPremiumUser) {
                // User Premium: Akses Semua
                // TODO: Jika ada status 'sudah_dikerjakan' dari API, ubah jadi "Lihat Hasil"
                boolean sudahDikerjakan = false; // Placeholder logika

                if (sudahDikerjakan) {
                    holder.btnKerjakan.setText("Lihat Hasil");
                    holder.btnKerjakan.setBackgroundTintList(context.getResources().getColorStateList(R.color.neo_yellow));
                    holder.btnKerjakan.setTextColor(context.getResources().getColor(R.color.black));
                } else {
                    holder.btnKerjakan.setText("Mulai Kerjakan");
                    holder.btnKerjakan.setBackgroundTintList(context.getResources().getColorStateList(R.color.neo_blue));
                }
                
                holder.btnKerjakan.setOnClickListener(v -> {
                    Intent intent = new Intent(context, DaftarSubtestActivity.class);
                    intent.putExtra("TRYOUT_ID", tryout.getId());
                    context.startActivity(intent);
                });

            } else {
                // User Gratis
                if (isTryoutGratis) {
                    // Tryout Gratis -> Kerjakan Sekarang
                    holder.btnKerjakan.setText("Kerjakan Sekarang");
                    holder.btnKerjakan.setBackgroundTintList(context.getResources().getColorStateList(R.color.neo_blue));
                    
                    holder.btnKerjakan.setOnClickListener(v -> {
                        Intent intent = new Intent(context, DaftarSubtestActivity.class);
                        intent.putExtra("TRYOUT_ID", tryout.getId());
                        context.startActivity(intent);
                    });
                } else {
                    // Tryout Premium -> Akses Premium
                    holder.btnKerjakan.setText("Upgrade Premium");
                    holder.btnKerjakan.setBackgroundColor(Color.parseColor("#4DD0E1")); // Biru Muda (Cyan)
                    
                    holder.btnKerjakan.setOnClickListener(v -> {
                        Intent intent = new Intent(context, UpgradePremiumActivity.class);
                        context.startActivity(intent);
                    });
                }
            }
        }
    }

    @Override
    public int getItemCount() {
        return tryoutList.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvTitle, tvSubtitle;
        Button btnKerjakan;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvTitle = itemView.findViewById(R.id.tv_title);
            tvSubtitle = itemView.findViewById(R.id.tv_subtitle);
            btnKerjakan = itemView.findViewById(R.id.btn_kerjakan);
        }
    }
}
