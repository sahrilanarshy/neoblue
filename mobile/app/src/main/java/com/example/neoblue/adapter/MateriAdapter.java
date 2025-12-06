package com.example.neoblue.adapter;

import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.graphics.PorterDuff;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.TextView;

import androidx.annotation.NonNull;
import androidx.core.content.ContextCompat;
import androidx.recyclerview.widget.RecyclerView;

import com.example.neoblue.IsiMateriActivity;
import com.example.neoblue.R;
import com.example.neoblue.UpgradePremiumActivity;
import com.example.neoblue.models.Materi;

import java.util.List;

public class MateriAdapter extends RecyclerView.Adapter<MateriAdapter.MateriViewHolder> {

    private final Context context;
    private final List<Materi> materiList;
    private boolean isUserPremium;

    public MateriAdapter(Context context, List<Materi> materiList, boolean isUserPremium) {
        this.context = context;
        this.materiList = materiList;
        this.isUserPremium = isUserPremium;
    }

    public void setUserPremium(boolean userPremium) {
        isUserPremium = userPremium;
        notifyDataSetChanged();
    }

    @NonNull
    @Override
    public MateriViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(context).inflate(R.layout.item_sub_materi, parent, false);
        return new MateriViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull MateriViewHolder holder, int position) {
        Materi materi = materiList.get(position);

        holder.tvSubJudul.setText(materi.getJudul());

        boolean isLocked = ("Premium".equalsIgnoreCase(materi.getTipe()) && !isUserPremium);

        if (isLocked) {
            // Premium state: Hide status label, show yellow button for consistency
            holder.tvStatusLabel.setVisibility(View.GONE);
            
            holder.imgIcon.setImageResource(R.drawable.ic_lock_outline_black_24dp);
            holder.imgIcon.setColorFilter(Color.WHITE, PorterDuff.Mode.SRC_IN);
            holder.iconContainer.setBackgroundResource(R.drawable.circle_bg_grey);

            holder.btnAction.setText("Upgrade Premium");
            // Use neo_yellow to make it look consistent with "Mulai" button
            holder.btnAction.setBackgroundTintList(ContextCompat.getColorStateList(context, R.color.neo_yellow));
            holder.btnAction.setTextColor(ContextCompat.getColor(context, android.R.color.black));
            holder.btnAction.setOnClickListener(v -> {
                context.startActivity(new Intent(context, UpgradePremiumActivity.class));
            });
        } else {
            // Free state: Show "Gratis" label, show yellow button
            if (isUserPremium) {
                 // If user is premium, hide the "Gratis" label even if content is free
                 holder.tvStatusLabel.setVisibility(View.GONE);
            } else {
                 holder.tvStatusLabel.setVisibility(View.VISIBLE);
                 holder.tvStatusLabel.setText("Gratis");
                 holder.tvStatusLabel.setBackgroundResource(R.drawable.bg_status_pill_green);
                 holder.tvStatusLabel.setTextColor(ContextCompat.getColor(context, R.color.text_green_dark));
            }

            holder.imgIcon.setImageResource(R.drawable.ic_materi_list);
            holder.imgIcon.setColorFilter(Color.WHITE, PorterDuff.Mode.SRC_IN);
            holder.iconContainer.setBackgroundResource(R.drawable.circle_bg_green);

            holder.btnAction.setText("Mulai");
            holder.btnAction.setBackgroundTintList(ContextCompat.getColorStateList(context, R.color.neo_yellow));
            holder.btnAction.setTextColor(ContextCompat.getColor(context, android.R.color.black));
            holder.btnAction.setOnClickListener(v -> {
                Intent intent = new Intent(context, IsiMateriActivity.class);
                intent.putExtra("MATERI_ID", materi.getId());
                context.startActivity(intent);
            });
        }
    }

    @Override
    public int getItemCount() {
        return materiList.size();
    }

    static class MateriViewHolder extends RecyclerView.ViewHolder {
        TextView tvSubJudul, tvStatusLabel;
        ImageView imgIcon;
        FrameLayout iconContainer;
        Button btnAction;

        public MateriViewHolder(@NonNull View itemView) {
            super(itemView);
            tvSubJudul = itemView.findViewById(R.id.tv_sub_judul);
            tvStatusLabel = itemView.findViewById(R.id.tv_status_label);
            imgIcon = itemView.findViewById(R.id.img_icon);
            iconContainer = itemView.findViewById(R.id.icon_container);
            btnAction = itemView.findViewById(R.id.btn_action);
        }
    }
}