package com.example.neoblue.adapter;

import android.content.Context;
import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.neoblue.DetailMateriActivity;
import com.example.neoblue.R;
import com.example.neoblue.models.Subtest;

import java.util.List;

public class HomeSubtestAdapter extends RecyclerView.Adapter<HomeSubtestAdapter.ViewHolder> {

    private List<Subtest> subtestList;
    private Context context;

    public HomeSubtestAdapter(Context context, List<Subtest> list) {
        this.context = context;
        this.subtestList = list;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        // Gunakan item_materi_card untuk tampilan seperti referensi (background biru muda, tombol kuning)
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_materi_card, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Subtest subtest = subtestList.get(position);
        
        // Set Kode (misal "PA" dari singkatan)
        String kode = subtest.getSingkatan() != null ? subtest.getSingkatan() : "Materi";
        holder.tvKode.setText(kode);
        
        // Set Judul Lengkap (misal "Penalaran Umum")
        holder.tvJudul.setText(subtest.getNamaSubtest());
        
        holder.btnMulai.setOnClickListener(v -> {
            Intent intent = new Intent(context, DetailMateriActivity.class);
            intent.putExtra("subtest_id", subtest.getId());
            intent.putExtra("nama_subtest", subtest.getNamaSubtest());
            context.startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return subtestList.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvKode, tvJudul;
        Button btnMulai;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvKode = itemView.findViewById(R.id.tv_kode);
            tvJudul = itemView.findViewById(R.id.tv_judul);
            btnMulai = itemView.findViewById(R.id.btn_mulai);
        }
    }
}
