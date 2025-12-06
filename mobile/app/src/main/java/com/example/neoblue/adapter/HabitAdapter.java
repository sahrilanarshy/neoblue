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

import com.example.neoblue.LatihanSoalActivity;
import com.example.neoblue.R;
import com.example.neoblue.models.Habit;

import java.util.List;

public class HabitAdapter extends RecyclerView.Adapter<HabitAdapter.HabitViewHolder> {

    private final Context context;
    private final List<Habit> habitList;

    public HabitAdapter(Context context, List<Habit> habitList) {
        this.context = context;
        this.habitList = habitList;
    }

    @NonNull
    @Override
    public HabitViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_habit, parent, false);
        return new HabitViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull HabitViewHolder holder, int position) {
        Habit habit = habitList.get(position);

        // Tanggal sudah ditampilkan di header fragment
        // Tampilkan Tipe (Soal/Bacaan) saja sebagai judul di list, bukan judul lengkap
        holder.tvJudul.setText(habit.getTipe());

        // Tampilkan tanggal pada setiap item jika tersedia
        String tanggal = habit.getTanggal();
        if (tanggal != null && !tanggal.isEmpty()) {
            // Jika item sebelumnya memiliki tanggal yang sama, sembunyikan tanggal untuk menghindari duplikat
            boolean showTanggal = true;
            if (position > 0) {
                Habit prev = habitList.get(position - 1);
                String prevTanggal = prev != null ? prev.getTanggal() : null;
                if (prevTanggal != null && prevTanggal.equals(tanggal)) {
                    showTanggal = false;
                }
            }

            if (showTanggal) {
                holder.tvGroupDate.setText(tanggal);
                holder.tvGroupDate.setVisibility(View.VISIBLE);
            } else {
                holder.tvGroupDate.setVisibility(View.GONE);
            }
        } else {
            holder.tvGroupDate.setVisibility(View.GONE);
        }
        
        holder.btnBuka.setOnClickListener(v -> {
            // Logika navigasi berdasarkan tipe habit
            Intent intent = new Intent(context, LatihanSoalActivity.class);
            intent.putExtra("HABIT_ID", habit.getId());
            intent.putExtra("HABIT_TYPE", habit.getTipe());
            context.startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return habitList.size();
    }

    static class HabitViewHolder extends RecyclerView.ViewHolder {
        TextView tvJudul;
        TextView tvGroupDate;
        Button btnBuka;

        public HabitViewHolder(@NonNull View itemView) {
            super(itemView);
            tvJudul = itemView.findViewById(R.id.tv_habit_judul);
            tvGroupDate = itemView.findViewById(R.id.tv_group_date);
            btnBuka = itemView.findViewById(R.id.btn_habit_buka);
        }
    }
}
