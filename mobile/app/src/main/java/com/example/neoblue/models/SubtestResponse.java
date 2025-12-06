package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class SubtestResponse {
    @SerializedName("status")
    private String status;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private List<Subtest> data;

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

    public List<Subtest> getData() {
        return data;
    }

    public void setData(List<Subtest> data) {
        this.data = data;
    }
}
