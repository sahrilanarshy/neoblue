package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;

public class Habit {
    @SerializedName("id")
    private String id;

    @SerializedName("tanggal")
    private String tanggal;

    @SerializedName("judul")
    private String judul;

    @SerializedName("tipe")
    private String tipe;

    @SerializedName("status")
    private String status;

    public String getId() {
        return id;
    }

    public void setId(String id) {
        this.id = id;
    }

    public String getTanggal() {
        return tanggal;
    }

    public void setTanggal(String tanggal) {
        this.tanggal = tanggal;
    }

    public String getJudul() {
        return judul;
    }

    public void setJudul(String judul) {
        this.judul = judul;
    }

    public String getTipe() {
        return tipe;
    }

    public void setTipe(String tipe) {
        this.tipe = tipe;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }
}
