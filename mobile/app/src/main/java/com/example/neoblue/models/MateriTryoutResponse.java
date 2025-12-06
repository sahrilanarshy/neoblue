package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;

import java.util.List;

public class MateriTryoutResponse {
    private String status;
    private List<Materi> data;

    private TryoutMeta tryout;

    public String getStatus() {
        return status;
    }

    public List<Materi> getData() {
        return data;
    }

    public TryoutMeta getTryout() {
        return tryout;
    }

    public static class TryoutMeta {
        @SerializedName("nama_tryout")
        private String namaTryout;

        public String getNamaTryout() {
            return namaTryout;
        }
    }

    public static class Materi {
        @SerializedName("subtest_id")
        private String id;

        @SerializedName("nama_subtest")
        private String nama_mapel;

        @SerializedName("jumlah_soal")
        private String jumlah_soal;

        @SerializedName("waktu_pengerjaan")
        private String durasi;

        @SerializedName("status")
        private int status;

        @SerializedName("score_display")
        private String nilai;

        @SerializedName("correct_count")
        private Integer correctCount;

        @SerializedName("incorrect_count")
        private Integer incorrectCount;

        @SerializedName("unanswered_count")
        private Integer unansweredCount;

        @SerializedName("total_questions")
        private Integer totalQuestions;

        public String getId() {
            return id;
        }

        public String getNamaMapel() {
            return nama_mapel;
        }

        public String getJumlahSoal() {
            return jumlah_soal;
        }

        public String getDurasi() {
            return durasi;
        }

        public int getStatus() {
            return status;
        }
        
        public String getNilai() {
            return nilai;
        }

        public Integer getCorrectCount() { return correctCount; }
        public Integer getIncorrectCount() { return incorrectCount; }
        public Integer getUnansweredCount() { return unansweredCount; }
        public Integer getTotalQuestions() { return totalQuestions; }
    }
}
