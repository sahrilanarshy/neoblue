package com.example.neoblue;

import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Toast;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.viewpager2.widget.ViewPager2;

import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.ShortResponse;

import java.util.ArrayList;
import java.util.List;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;
import android.util.Log;

public class ShortFragment extends Fragment {

    private ViewPager2 viewPager;
    private VideoShortAdapter adapter;
    private List<VideoShortAdapter.VideoItem> videoItems;
    

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_short, container, false);

        viewPager = view.findViewById(R.id.viewpager_shorts);
        videoItems = new ArrayList<>();

        // Cek Status Premium
        SharedPreferences preferences = getContext().getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String tipeUser = preferences.getString("tipe_user", "Free");
        boolean isPremium = "Premium".equalsIgnoreCase(tipeUser);

        adapter = new VideoShortAdapter(videoItems, getContext(), isPremium);
        viewPager.setAdapter(adapter);

        fetchShorts();

        return view;
    }

    private void fetchShorts() {
        ApiService apiService = ApiConfig.getApiService();
        apiService.getShorts().enqueue(new Callback<ShortResponse>() {
            @Override
            public void onResponse(@NonNull Call<ShortResponse> call, @NonNull Response<ShortResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    ShortResponse result = response.body();
                    if ("success".equals(result.getStatus()) && result.getData() != null) {
                        videoItems.clear();
                        for (ShortResponse.ShortData data : result.getData()) {
                            // Construct full video URL if needed (adjust based on your server path)
                            // Assuming video_path is something like "uploads/video.mp4"
                            String fullVideoUrl = data.getVideoPath();
                            if (!fullVideoUrl.startsWith("http")) {
                                fullVideoUrl = ApiConfig.BASE_URL.replace("api/", "") + data.getVideoPath();
                            }
                            Log.d("ShortFragment", "Short item id=" + data.getId() + " tipe=" + data.getTipe() + " path=" + data.getVideoPath() + " fullUrl=" + fullVideoUrl);
                            
                                boolean itemIsPremium = "premium".equalsIgnoreCase(data.getTipe()) || "Premium".equalsIgnoreCase(data.getTipe());
                                videoItems.add(new VideoShortAdapter.VideoItem(
                                    fullVideoUrl,
                                    data.getJudul(),
                                    data.getTanggalUpload(),
                                    itemIsPremium
                                ));
                        }
                        // If user is Free, lock the last short (so when they reach it they see upgrade)
                        SharedPreferences preferences = getContext().getSharedPreferences("user_session", Context.MODE_PRIVATE);
                        String tipeUser = preferences.getString("tipe_user", "Free");
                        boolean isPremiumUser = "Premium".equalsIgnoreCase(tipeUser);
                        Log.d("ShortFragment", "Loaded shorts count=" + videoItems.size() + " isPremiumUser=" + isPremiumUser);
                        // Filter out premium items for Free users
                        if (!isPremiumUser) {
                            List<VideoShortAdapter.VideoItem> filtered = new ArrayList<>();
                            for (VideoShortAdapter.VideoItem vi : videoItems) {
                                if (!vi.isPremium) filtered.add(vi);
                            }
                            videoItems.clear();
                            videoItems.addAll(filtered);
                            // Append an upgrade card at the end so Free users see a prompt
                            if (videoItems.size() > 0) {
                                videoItems.add(new VideoShortAdapter.VideoItem(
                                    null,
                                    "Jadi Premium",
                                    "Untuk melihat konten Premium, jadi member sekarang.",
                                    false,
                                    true
                                ));
                            }
                        }
                        adapter.notifyDataSetChanged();
                    } else {
                         Toast.makeText(getContext(), "Gagal memuat shorts: " + result.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                } else {
                    Toast.makeText(getContext(), "Server Error: " + response.code(), Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(@NonNull Call<ShortResponse> call, @NonNull Throwable t) {
                Toast.makeText(getContext(), "Koneksi Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}
