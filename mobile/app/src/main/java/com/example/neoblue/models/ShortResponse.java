package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class ShortResponse {
    @SerializedName("status")
    private String status;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private List<ShortData> data;

    public String getStatus() {
        return status;
    }

    public String getMessage() {
        return message;
    }

    public List<ShortData> getData() {
        return data;
    }

    public static class ShortData {
        @SerializedName("id")
        private String id;

        @SerializedName("judul")
        private String judul;

        @SerializedName("tanggal_upload")
        private String tanggalUpload;

        @SerializedName("tipe")
        private String tipe;

        @SerializedName("video_path")
        private String videoPath;

        public String getId() {
            return id;
        }

        public String getJudul() {
            return judul;
        }

        public String getTanggalUpload() {
            return tanggalUpload;
        }

        public String getTipe() {
            return tipe;
        }

        public String getVideoPath() {
            return videoPath;
        }
    }
}
