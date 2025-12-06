package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class DetailMateriResponse {
    @SerializedName("subtest")
    private Subtest subtest;

    @SerializedName("materi")
    private List<Materi> materi;

    public Subtest getSubtest() {
        return subtest;
    }

    public void setSubtest(Subtest subtest) {
        this.subtest = subtest;
    }

    public List<Materi> getMateri() {
        return materi;
    }

    public void setMateri(List<Materi> materi) {
        this.materi = materi;
    }
}
