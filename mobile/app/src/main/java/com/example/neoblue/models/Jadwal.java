package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;

public class Jadwal {
    @SerializedName("id")
    private int id;

    @SerializedName("hari")
    private String hari;

    @SerializedName("jam_mulai")
    private String jamMulai;

    @SerializedName("jam_selesai")
    private String jamSelesai;

    @SerializedName("nama_subtest")
    private String namaSubtest;

    @SerializedName("singkatan")
    private String singkatan;

    public int getId() {
        return id;
    }

    public String getHari() {
        return hari;
    }

    public String getJamMulai() {
        return jamMulai;
    }

    public String getJamSelesai() {
        return jamSelesai;
    }

    public String getNamaSubtest() {
        return namaSubtest;
    }

    public String getSingkatan() {
        return singkatan;
    }
}
