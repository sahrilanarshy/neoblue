package com.example.neoblue;

import android.content.Context;
import android.content.Intent;
import android.media.MediaPlayer;
import android.net.Uri;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
// removed unused Button and FrameLayout imports after overlay removal
import android.widget.LinearLayout;
import android.widget.Button;
import android.widget.TextView;
import android.widget.VideoView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import java.util.List;

public class VideoShortAdapter extends RecyclerView.Adapter<VideoShortAdapter.VideoViewHolder> {

    private List<VideoItem> videoItems;
    private Context context;
    private boolean isPremiumUser = false; // Default user Free
    private static final int DEFAULT_FREE_LIMIT = 3; // Default batas video untuk user free
    private int accessibleLimit = -1; // if >=0, use this as the first locked index (position >= accessibleLimit locked)

    public VideoShortAdapter(List<VideoItem> videoItems, Context context, boolean isPremium) {
        this.videoItems = videoItems;
        this.context = context;
        this.isPremiumUser = isPremium;
    }

    public void setAccessibleLimit(int limit) {
        this.accessibleLimit = limit;
    }

    @NonNull
    @Override
    public VideoViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_video_shorts, parent, false);
        return new VideoViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull VideoViewHolder holder, int position) {
        // Logika: Jika user Free dan bukan premium, kunci jika item premium atau melewati limit
        int limitToUse = (accessibleLimit >= 0) ? accessibleLimit : DEFAULT_FREE_LIMIT;
        VideoItem item = videoItems.get(position);
        boolean isItemPremium = false;
        try {
            isItemPremium = item.isPremium;
        } catch (Exception ignored) {
        }
        boolean isLockedByPosition = !isPremiumUser && position >= limitToUse;
        boolean isLocked = (!isPremiumUser && isItemPremium) || isLockedByPosition;
        Log.d("VideoShortAdapter", "bind pos=" + position + " isPremiumUser=" + isPremiumUser + " item.isPremium=" + isItemPremium + " limit=" + limitToUse + " isLocked=" + isLocked);
        holder.setVideoData(item, isLocked, context);
    }

    @Override
    public int getItemCount() {
        return videoItems.size();
    }

    static class VideoViewHolder extends RecyclerView.ViewHolder {
        VideoView videoView;
        TextView title, desc;
        LinearLayout layoutInfo;
        View layoutUpgradeCard;
        View layoutUpgradeContainer;
        Button btnUpgradeAction;
        Button btnUpgradeDismiss;
        boolean showUpgradeOnCompletion = false;

        public VideoViewHolder(@NonNull View itemView) {
            super(itemView);
            videoView = itemView.findViewById(R.id.video_view);
            title = itemView.findViewById(R.id.tv_title_short);
            desc = itemView.findViewById(R.id.tv_desc_short);
            layoutInfo = itemView.findViewById(R.id.layout_info_video);
            layoutUpgradeCard = itemView.findViewById(R.id.layout_upgrade_card);
            layoutUpgradeContainer = itemView.findViewById(R.id.layout_upgrade_container);
            btnUpgradeAction = itemView.findViewById(R.id.btn_upgrade_action);
            btnUpgradeDismiss = itemView.findViewById(R.id.btn_upgrade_dismiss);
        }

        void setVideoData(VideoItem videoItem, boolean isLocked, Context context) {
            // Set title/description and default visibility
            title.setText(videoItem.videoTitle);
            desc.setText(videoItem.videoDesc);
            videoView.setVisibility(View.VISIBLE);
            layoutInfo.setVisibility(View.VISIBLE);
            if (layoutUpgradeCard != null) layoutUpgradeCard.setVisibility(View.GONE);
            if (layoutUpgradeContainer != null) layoutUpgradeContainer.setVisibility(View.GONE);
            // Configure upgrade behavior per-item (no overlay shown here)
            showUpgradeOnCompletion = isLocked;

            // If this item is an upgrade card, display the upgrade UI and return
            if (videoItem.isUpgradeCard) {
                videoView.setVisibility(View.GONE);
                layoutInfo.setVisibility(View.GONE);
                if (layoutUpgradeContainer != null) layoutUpgradeContainer.setVisibility(View.VISIBLE);
                if (layoutUpgradeCard != null) layoutUpgradeCard.setVisibility(View.VISIBLE);
                if (btnUpgradeAction != null) btnUpgradeAction.setOnClickListener(v -> {
                    try {
                        Intent intent = new Intent(context, UpgradePremiumActivity.class);
                        context.startActivity(intent);
                    } catch (Exception ex) {
                        Log.e("VideoShortAdapter", "Error launching upgrade: " + ex.getMessage());
                    }
                });
                if (btnUpgradeDismiss != null) btnUpgradeDismiss.setOnClickListener(v -> {
                    try {
                        // Dismiss: hide the whole container (user can scroll back)
                        if (layoutUpgradeContainer != null) layoutUpgradeContainer.setVisibility(View.GONE);
                        if (layoutUpgradeCard != null) layoutUpgradeCard.setVisibility(View.GONE);
                        layoutInfo.setVisibility(View.VISIBLE);
                    } catch (Exception ex) {
                        Log.e("VideoShortAdapter", "Error dismissing upgrade card: " + ex.getMessage());
                    }
                });
                return;
            }

            // Safety checks for URL
            try {
                if (videoItem.videoUrl == null || videoItem.videoUrl.trim().isEmpty()) {
                    // No URL - hide video and info, log for debugging
                    videoView.setVisibility(View.GONE);
                    layoutInfo.setVisibility(View.GONE);
                    Log.w("VideoShortAdapter", "Video unavailable: " + (videoItem.videoTitle == null ? "(no title)" : videoItem.videoTitle));
                    return;
                }

                Uri uri = Uri.parse(videoItem.videoUrl);
                try {
                    if (videoView.isPlaying()) videoView.stopPlayback();
                } catch (Exception ignore) {
                }
                videoView.setVideoURI(uri);
                videoView.requestFocus();

                // Reset listeners to avoid reuse issues on recycled holders
                videoView.setOnPreparedListener(null);
                videoView.setOnErrorListener(null);
                videoView.setOnCompletionListener(null);

                videoView.setOnPreparedListener(new MediaPlayer.OnPreparedListener() {
                    @Override
                    public void onPrepared(MediaPlayer mp) {
                        try {
                            mp.start();
                        } catch (Exception e) {
                            Log.e("VideoShortAdapter", "Error starting video: " + e.getMessage());
                        }

                        // Defensive: avoid scaling/math errors when dimensions are zero
                        try {
                            int videoW = mp.getVideoWidth();
                            int videoH = mp.getVideoHeight();
                            int viewW = videoView.getWidth();
                            int viewH = videoView.getHeight();
                            if (videoW > 0 && videoH > 0 && viewW > 0 && viewH > 0) {
                                float videoRatio = videoW / (float) videoH;
                                float screenRatio = viewW / (float) viewH;
                                float scale = videoRatio / screenRatio;
                                if (Float.isFinite(scale)) {
                                    if (scale >= 1f) {
                                        videoView.setScaleX(scale);
                                        videoView.setScaleY(1f);
                                    } else {
                                        videoView.setScaleY(1f / scale);
                                        videoView.setScaleX(1f);
                                    }
                                }
                            }
                        } catch (Exception e) {
                            Log.e("VideoShortAdapter", "Scaling error: " + e.getMessage());
                        }
                    }
                });

                // Handle playback errors gracefully to avoid app crash/return
                videoView.setOnErrorListener(new MediaPlayer.OnErrorListener() {
                    @Override
                    public boolean onError(MediaPlayer mp, int what, int extra) {
                        Log.e("VideoShortAdapter", "MediaPlayer error what=" + what + " extra=" + extra);
                        try {
                            if (videoView.isPlaying()) videoView.stopPlayback();
                        } catch (Exception ignore) {
                        }
                        // Hide playback UI and log error; don't show overlay here
                        try {
                            videoView.setVisibility(View.GONE);
                            layoutInfo.setVisibility(View.GONE);
                            Log.e("VideoShortAdapter", "MediaPlayer error, hidden playback views");
                        } catch (Exception e) {
                            Log.e("VideoShortAdapter", "Error handling media error UI: " + e.getMessage());
                        }
                        return true; // handled
                    }
                });

                videoView.setOnCompletionListener(new MediaPlayer.OnCompletionListener() {
                    @Override
                    public void onCompletion(MediaPlayer mp) {
                        try {
                            Log.d("VideoShortAdapter", "onCompletion called; showUpgradeOnCompletion=" + showUpgradeOnCompletion);
                                if (showUpgradeOnCompletion) {
                                    try { mp.pause(); } catch (Exception ignore) {}
                                    videoView.setVisibility(View.GONE);
                                    layoutInfo.setVisibility(View.GONE);
                                    Log.d("VideoShortAdapter", "Playback completed for locked item; hidden views.");
                                } else {
                                    mp.start(); // Loop for normal videos
                                }
                        } catch (Exception e) {
                            Log.e("VideoShortAdapter", "Completion handler error: " + e.getMessage());
                        }
                    }
                });

                // Allow user to tap video to pause/play
                videoView.setOnClickListener(v -> {
                    try {
                        if (videoView.isPlaying()) videoView.pause();
                        else videoView.start();
                    } catch (Exception e) {
                        Log.e("VideoShortAdapter", "Play/Pause error: " + e.getMessage());
                    }
                });

            } catch (Exception ex) {
                Log.e("VideoShortAdapter", "Failed to load video URI: " + ex.getMessage());
                videoView.setVisibility(View.GONE);
                layoutInfo.setVisibility(View.GONE);
                Log.e("VideoShortAdapter", "Failed to load video, hidden playback views");
            }
        }
    }

    public static class VideoItem {
        public String videoUrl;
        public String videoTitle;
        public String videoDesc;
        public boolean isPremium;
        public boolean isUpgradeCard = false;

        public VideoItem(String videoUrl, String videoTitle, String videoDesc) {
            this(videoUrl, videoTitle, videoDesc, false);
        }

        public VideoItem(String videoUrl, String videoTitle, String videoDesc, boolean isPremium) {
            this(videoUrl, videoTitle, videoDesc, isPremium, false);
        }

        public VideoItem(String videoUrl, String videoTitle, String videoDesc, boolean isPremium, boolean isUpgradeCard) {
            this.videoUrl = videoUrl;
            this.videoTitle = videoTitle;
            this.videoDesc = videoDesc;
            this.isPremium = isPremium;
            this.isUpgradeCard = isUpgradeCard;
        }
    }
}
