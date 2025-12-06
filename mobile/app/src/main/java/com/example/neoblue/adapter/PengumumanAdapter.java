package com.example.neoblue.adapter;

import android.content.Context;
import android.content.Intent;
import android.net.Uri;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;

import com.example.neoblue.R;
import com.example.neoblue.models.PengumumanResponse;

import java.util.List;

public class PengumumanAdapter extends RecyclerView.Adapter<PengumumanAdapter.ViewHolder> {

    private List<PengumumanResponse.Pengumuman> pengumumanList;
    private Context context;

    public PengumumanAdapter(Context context, List<PengumumanResponse.Pengumuman> list) {
        this.context = context;
        this.pengumumanList = list;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_notifikasi, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        PengumumanResponse.Pengumuman p = pengumumanList.get(position);
        
        holder.tvJudul.setText(p.getJudul());
        holder.tvTanggal.setText(p.getTanggalTerbit());
        holder.tvIsi.setText(p.getIsi());
        
        if (p.getLink() != null && !p.getLink().isEmpty()) {
            holder.tvLink.setVisibility(View.VISIBLE);
            holder.tvLink.setOnClickListener(v -> {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(p.getLink()));
                context.startActivity(browserIntent);
            });
        } else {
            holder.tvLink.setVisibility(View.GONE);
        }
    }

    @Override
    public int getItemCount() {
        return pengumumanList.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvJudul, tvTanggal, tvIsi, tvLink;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvJudul = itemView.findViewById(R.id.tv_judul_notif);
            tvTanggal = itemView.findViewById(R.id.tv_tanggal_notif);
            tvIsi = itemView.findViewById(R.id.tv_isi_notif);
            tvLink = itemView.findViewById(R.id.tv_link_notif);
        }
    }
}
