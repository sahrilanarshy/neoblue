package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class MateriSubtestResponse {
    @SerializedName("status")
    private String status;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private MateriSubtestData data;

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

    public MateriSubtestData getData() {
        return data;
    }

    public void setData(MateriSubtestData data) {
        this.data = data;
    }

    public static class MateriSubtestData {
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
}
