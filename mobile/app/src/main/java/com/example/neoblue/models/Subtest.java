package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;

public class Subtest {
    @SerializedName("id")
    private String id; // Changed from int to String

    @SerializedName("nama_subtest")
    private String namaSubtest;

    @SerializedName("singkatan")
    private String singkatan;

    public String getId() { // Changed return type to String
        return id;
    }

    public void setId(String id) { // Changed parameter type to String
        this.id = id;
    }

    public String getNamaSubtest() {
        return namaSubtest;
    }

    public void setNamaSubtest(String namaSubtest) {
        this.namaSubtest = namaSubtest;
    }

    public String getSingkatan() {
        return singkatan;
    }

    public void setSingkatan(String singkatan) {
        this.singkatan = singkatan;
    }
}