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
import com.example.neoblue.models.NotificationResponse;

import java.util.List;

public class NotificationAdapter extends RecyclerView.Adapter<NotificationAdapter.ViewHolder> {

    private List<NotificationResponse.Notification> list;
    private Context context;

    public NotificationAdapter(Context context, List<NotificationResponse.Notification> list) {
        this.context = context;
        this.list = list;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_notifikasi, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        NotificationResponse.Notification n = list.get(position);

        holder.tvJudul.setText(n.getTitle());
        // created_at could be null; display created_at or empty
        String date = n.getCreated_at() != null ? n.getCreated_at() : "";
        holder.tvTanggal.setText(date);
        holder.tvIsi.setText(n.getMessage());

        // Untuk notifikasi pembayaran ditolak, jangan tampilkan atau buka link di mobile
        boolean isPaymentRejected = n.getTitle() != null && n.getTitle().toLowerCase().contains("pembayaran ditolak");
        boolean linkIsKonfirmasi = n.getLink() != null && n.getLink().contains("konfirmasi");

        if (!isPaymentRejected && n.getLink() != null && !n.getLink().isEmpty() && !linkIsKonfirmasi) {
            holder.tvLink.setVisibility(View.VISIBLE);
            holder.tvLink.setOnClickListener(v -> {
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(n.getLink()));
                context.startActivity(browserIntent);
            });
        } else {
            holder.tvLink.setVisibility(View.GONE);
        }

        // Ketika item diklik, tandai sebagai dibaca (jika personal dan belum dibaca)
        holder.itemView.setOnClickListener(v -> {
            String notifId = n.getId();
            if (notifId == null) return;
            // Jika global pengumuman (id mulai 'peng_'), panggil API mark read untuk pengumuman
            boolean isGlobal = notifId != null && notifId.startsWith("peng_");
            android.content.SharedPreferences prefs = context.getSharedPreferences("user_session", Context.MODE_PRIVATE);
            String userId = prefs.getString("user_id", "");
            if (userId == null || userId.isEmpty()) return;

            if (isGlobal) {
                if (n.getIs_read() != null && n.getIs_read().equals("0")) {
                    com.example.neoblue.api.ApiService api = com.example.neoblue.api.ApiConfig.getApiService();
                    retrofit2.Call<okhttp3.ResponseBody> r = api.markPengumumanRead(userId, notifId);
                    r.enqueue(new retrofit2.Callback<okhttp3.ResponseBody>() {
                        @Override
                        public void onResponse(retrofit2.Call<okhttp3.ResponseBody> call, retrofit2.Response<okhttp3.ResponseBody> response) {
                            if (response.isSuccessful()) {
                                // update unread badge in prefs (decrement)
                                int cnt = prefs.getInt("notif_unread_count", 0);
                                prefs.edit().putInt("notif_unread_count", Math.max(0, cnt-1)).apply();
                            }
                        }

                        @Override
                        public void onFailure(retrofit2.Call<okhttp3.ResponseBody> call, Throwable t) {
                        }
                    });
                }
                return;
            }

            // Jika personal numeric id -> mark as read old behavior
            try {
                Integer.parseInt(notifId);
            } catch (Exception ex) {
                // bukan personal numeric id -> tidak perlu mark as read
                return;
            }

            if (n.getIs_read() != null && n.getIs_read().equals("0")) {
                com.example.neoblue.api.ApiService api = com.example.neoblue.api.ApiConfig.getApiService();
                retrofit2.Call<okhttp3.ResponseBody> r = api.markNotificationRead(userId, notifId);
                r.enqueue(new retrofit2.Callback<okhttp3.ResponseBody>() {
                    @Override
                    public void onResponse(retrofit2.Call<okhttp3.ResponseBody> call, retrofit2.Response<okhttp3.ResponseBody> response) {
                        if (response.isSuccessful()) {
                            int cnt = prefs.getInt("notif_unread_count", 0);
                            prefs.edit().putInt("notif_unread_count", Math.max(0, cnt-1)).apply();
                        }
                    }

                    @Override
                    public void onFailure(retrofit2.Call<okhttp3.ResponseBody> call, Throwable t) {
                    }
                });
            }
        });

        // Tombol hapus notifikasi (hanya untuk notifikasi personal dengan id numeric)
        holder.btnDelete.setOnClickListener(v -> {
            // Tampilkan konfirmasi lalu panggil API yang sesuai (hide untuk pengumuman global, delete untuk personal)
            android.content.SharedPreferences prefs = context.getSharedPreferences("user_session", Context.MODE_PRIVATE);
            String userId = prefs.getString("user_id", "");
            if (userId == null || userId.isEmpty()) {
                android.widget.Toast.makeText(context, "User tidak terautentikasi", android.widget.Toast.LENGTH_SHORT).show();
                return;
            }

            String notifId = n.getId();
            boolean isGlobal = notifId != null && notifId.startsWith("peng_");

            android.app.AlertDialog.Builder builder = new android.app.AlertDialog.Builder(context);
            builder.setTitle("Konfirmasi");
            builder.setMessage(isGlobal ? "Sembunyikan pengumuman ini secara permanen untuk akun Anda?" : "Hapus notifikasi ini?");
            builder.setNegativeButton("Batal", (dialog, which) -> dialog.dismiss());
            builder.setPositiveButton("Hapus", (dialog, which) -> {
                if (isGlobal) {
                    // panggil API hidePengumuman
                    com.example.neoblue.api.ApiService api = com.example.neoblue.api.ApiConfig.getApiService();
                    retrofit2.Call<okhttp3.ResponseBody> call = api.hidePengumuman(userId, notifId);
                    call.enqueue(new retrofit2.Callback<okhttp3.ResponseBody>() {
                        @Override
                        public void onResponse(retrofit2.Call<okhttp3.ResponseBody> call, retrofit2.Response<okhttp3.ResponseBody> response) {
                            if (response.isSuccessful()) {
                                list.remove(position);
                                notifyItemRemoved(position);
                                notifyItemRangeChanged(position, list.size());
                                android.widget.Toast.makeText(context, "Pengumuman disembunyikan", android.widget.Toast.LENGTH_SHORT).show();
                            } else {
                                android.widget.Toast.makeText(context, "Gagal menyembunyikan pengumuman", android.widget.Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(retrofit2.Call<okhttp3.ResponseBody> call, Throwable t) {
                            android.widget.Toast.makeText(context, "Koneksi gagal: " + t.getMessage(), android.widget.Toast.LENGTH_SHORT).show();
                        }
                    });
                } else {
                    // Pastikan notifId numeric sebelum memanggil delete API
                    try {
                        Integer.parseInt(notifId);
                    } catch (Exception ex) {
                        android.widget.Toast.makeText(context, "Notifikasi ini tidak dapat dihapus di aplikasi.", android.widget.Toast.LENGTH_SHORT).show();
                        return;
                    }

                    com.example.neoblue.api.ApiService api = com.example.neoblue.api.ApiConfig.getApiService();
                    retrofit2.Call<okhttp3.ResponseBody> call = api.deleteNotification(userId, notifId);
                    call.enqueue(new retrofit2.Callback<okhttp3.ResponseBody>() {
                        @Override
                        public void onResponse(retrofit2.Call<okhttp3.ResponseBody> call, retrofit2.Response<okhttp3.ResponseBody> response) {
                            if (response.isSuccessful()) {
                                // Hapus item dari list dan update adapter
                                list.remove(position);
                                notifyItemRemoved(position);
                                notifyItemRangeChanged(position, list.size());
                                android.widget.Toast.makeText(context, "Notifikasi dihapus", android.widget.Toast.LENGTH_SHORT).show();
                                // Perbarui badge unread (kurangi jika item ini unread)
                                if (n.getIs_read() != null && n.getIs_read().equals("0")) {
                                    android.content.SharedPreferences prefs2 = context.getSharedPreferences("user_session", Context.MODE_PRIVATE);
                                    int cnt = prefs2.getInt("notif_unread_count", 0);
                                    prefs2.edit().putInt("notif_unread_count", Math.max(0, cnt-1)).apply();
                                }
                            } else {
                                android.widget.Toast.makeText(context, "Gagal menghapus notifikasi", android.widget.Toast.LENGTH_SHORT).show();
                            }
                        }

                        @Override
                        public void onFailure(retrofit2.Call<okhttp3.ResponseBody> call, Throwable t) {
                            android.widget.Toast.makeText(context, "Koneksi gagal: " + t.getMessage(), android.widget.Toast.LENGTH_SHORT).show();
                        }
                    });
                }
            });
            builder.show();
        });
    }

    @Override
    public int getItemCount() {
        return list.size();
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvJudul, tvTanggal, tvIsi, tvLink;
        android.widget.ImageButton btnDelete;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvJudul = itemView.findViewById(R.id.tv_judul_notif);
            tvTanggal = itemView.findViewById(R.id.tv_tanggal_notif);
            tvIsi = itemView.findViewById(R.id.tv_isi_notif);
            tvLink = itemView.findViewById(R.id.tv_link_notif);
            btnDelete = itemView.findViewById(R.id.btn_delete_notif);
        }
    }
}
