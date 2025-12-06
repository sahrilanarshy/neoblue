package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;

public class ProfileResponse {
    @SerializedName("status")
    private String status;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private Data data;

    public String getStatus() {
        return status;
    }

    public String getMessage() {
        return message;
    }

    public Data getData() {
        return data;
    }

    public static class Data {
        @SerializedName("id")
        private String id;

        @SerializedName("nama")
        private String nama;

        @SerializedName("email")
        private String email;

        @SerializedName("telepon")
        private String telepon;

        @SerializedName("foto")
        private String foto;

        @SerializedName("tipe_user")
        private String tipeUser; // "Free" or "Premium"

        public String getId() {
            return id;
        }

        public String getNama() {
            return nama;
        }

        public String getEmail() {
            return email;
        }

        public String getTelepon() {
            return telepon;
        }

        public String getFoto() {
            return foto;
        }

        public String getTipeUser() {
            return tipeUser;
        }
    }
}
