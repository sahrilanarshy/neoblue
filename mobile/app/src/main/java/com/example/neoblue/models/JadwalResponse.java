package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class JadwalResponse {
    @SerializedName("status")
    private String status;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private List<Jadwal> data;

    public String getStatus() {
        return status;
    }

    public String getMessage() {
        return message;
    }

    public List<Jadwal> getData() {
        return data;
    }
}
