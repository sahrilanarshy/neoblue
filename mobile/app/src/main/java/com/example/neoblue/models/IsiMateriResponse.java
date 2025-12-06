package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;

public class IsiMateriResponse {
    @SerializedName("status")
    private String status;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private IsiMateriData data;

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public IsiMateriData getData() {
        return data;
    }

    public void setData(IsiMateriData data) {
        this.data = data;
    }

    public static class IsiMateriData {
        @SerializedName("id")
        private String id;

        @SerializedName("judul")
        private String judul;

        @SerializedName("deskripsi")
        private String deskripsi;

        // Accept both `video_url` and `link` keys from the API.
        @SerializedName("video_url")
        private String videoUrl;

        @SerializedName("link")
        private String link;

        public String getId() {
            return id;
        }

        public void setId(String id) {
            this.id = id;
        }

        public String getJudul() {
            return judul;
        }

        public void setJudul(String judul) {
            this.judul = judul;
        }

        public String getDeskripsi() {
            return deskripsi;
        }

        public void setDeskripsi(String deskripsi) {
            this.deskripsi = deskripsi;
        }

        // Return `video_url` if present, otherwise fall back to `link`.
        public String getVideoUrl() {
            if (videoUrl != null && !videoUrl.isEmpty()) return videoUrl;
            if (link != null && !link.isEmpty()) return link;
            return null;
        }

        public void setVideoUrl(String videoUrl) {
            this.videoUrl = videoUrl;
        }

        public String getLink() {
            return link;
        }

        public void setLink(String link) {
            this.link = link;
        }
    }

    // Convenience getters so existing code that expects flat fields keeps working.
    public String getJudul() {
        if (data != null) return data.getJudul();
        return null;
    }

    public String getDeskripsi() {
        if (data != null) return data.getDeskripsi();
        return null;
    }

    public String getVideoUrl() {
        if (data != null) return data.getVideoUrl();
        return null;
    }
}
