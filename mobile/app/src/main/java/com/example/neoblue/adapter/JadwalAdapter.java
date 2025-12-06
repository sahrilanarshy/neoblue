package com.example.neoblue.adapter;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.example.neoblue.R;
import com.example.neoblue.models.Jadwal;
import java.util.List;

public class JadwalAdapter extends RecyclerView.Adapter<JadwalAdapter.JadwalViewHolder> {

    private Context context;
    private List<Jadwal> jadwalList;
    private OnItemClickListener listener;

    public interface OnItemClickListener {
        void onDeleteClick(int position);
    }

    public void setOnItemClickListener(OnItemClickListener listener) {
        this.listener = listener;
    }

    public JadwalAdapter(Context context, List<Jadwal> jadwalList) {
        this.context = context;
        this.jadwalList = jadwalList;
    }

    @NonNull
    @Override
    public JadwalViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_jadwal, parent, false);
        return new JadwalViewHolder(view, listener);
    }

    @Override
    public void onBindViewHolder(@NonNull JadwalViewHolder holder, int position) {
        Jadwal jadwal = jadwalList.get(position);
        holder.tvHari.setText(jadwal.getHari());
        holder.tvMapel.setText(jadwal.getNamaSubtest());
        holder.tvJam.setText("🕒 " + jadwal.getJamMulai() + " - " + jadwal.getJamSelesai());
    }

    @Override
    public int getItemCount() {
        return jadwalList.size();
    }

    public static class JadwalViewHolder extends RecyclerView.ViewHolder {
        TextView tvHari, tvMapel, tvJam;
        FrameLayout btnDelete;

        public JadwalViewHolder(@NonNull View itemView, final OnItemClickListener listener) {
            super(itemView);
            tvHari = itemView.findViewById(R.id.tv_hari_item);
            tvMapel = itemView.findViewById(R.id.tv_mapel_item);
            tvJam = itemView.findViewById(R.id.tv_jam_item);
            btnDelete = itemView.findViewById(R.id.btn_delete_jadwal);

            btnDelete.setOnClickListener(v -> {
                if (listener != null) {
                    int position = getAdapterPosition();
                    if (position != RecyclerView.NO_POSITION) {
                        listener.onDeleteClick(position);
                    }
                }
            });
        }
    }
}
