package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;

public class Materi {
    @SerializedName("id")
    private String id; // Changed from int to String

    @SerializedName("judul")
    private String judul;

    @SerializedName("deskripsi")
    private String deskripsi;

    @SerializedName("tipe")
    private String tipe;

    @SerializedName("is_accessible")
    private int isAccessible;

    public String getId() { // Changed return type to String
        return id;
    }

    public void setId(String id) { // Changed parameter type to String
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

    public String getTipe() {
        return tipe;
    }

    public void setTipe(String tipe) {
        this.tipe = tipe;
    }

    public int getIsAccessible() {
        return isAccessible;
    }

    public void setIsAccessible(int isAccessible) {
        this.isAccessible = isAccessible;
    }
}
