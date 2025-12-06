package com.example.neoblue;

import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.fragment.app.Fragment;
import androidx.viewpager2.widget.ViewPager2;
import java.util.ArrayList;
import java.util.List;

public class FragmentShortsActivity extends Fragment {

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_short, container, false);

        final ViewPager2 viewPager = view.findViewById(R.id.viewpager_shorts);

        List<VideoShortAdapter.VideoItem> videoItems = new ArrayList<>();
        // Video 1 (Free)
        videoItems.add(new VideoShortAdapter.VideoItem(
                "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4", 
                "Big Buck Bunny", 
                "Video animasi pendek yang lucu."
        ));
        // Video 2 (Free)
        videoItems.add(new VideoShortAdapter.VideoItem(
                "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4", 
                "Elephants Dream", 
                "Kisah surealis mesin dan manusia."
        ));
        // Video 3 (Free)
         videoItems.add(new VideoShortAdapter.VideoItem(
                "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4", 
                "For Bigger Blazes", 
                "Contoh video lain."
        ));

        // Tambahkan Video ke-4 dan ke-5 untuk MENGUJI TAMPILAN TERKUNCI (PREMIUM)
        // Video 4 (Locked jika user Free)
        videoItems.add(new VideoShortAdapter.VideoItem(
                "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4", 
                "Video Premium 1", 
                "Konten ini khusus Premium.",
                true
        ));
        // Video 5 (Locked jika user Free)
        videoItems.add(new VideoShortAdapter.VideoItem(
                "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4", 
                "Video Premium 2", 
                "Silakan upgrade untuk menonton.",
                true
        ));

        // Ambil status Premium
        SharedPreferences preferences = getContext().getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String tipeUser = preferences.getString("tipe_user", "Free");
        boolean isPremium = "Premium".equalsIgnoreCase(tipeUser);

                // If the current user is not Premium, filter out premium shorts entirely.
                List<VideoShortAdapter.VideoItem> visibleItems;
                if (!isPremium) {
                        visibleItems = new ArrayList<>();
                        for (VideoShortAdapter.VideoItem it : videoItems) {
                                if (!it.isPremium) visibleItems.add(it);
                        }
                } else {
                        visibleItems = videoItems;
                }

                VideoShortAdapter adapter = new VideoShortAdapter(visibleItems, getContext(), isPremium);
                viewPager.setAdapter(adapter);

        return view;
    }
}