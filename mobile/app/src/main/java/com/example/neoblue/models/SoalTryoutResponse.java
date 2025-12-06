package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class SoalTryoutResponse {
    @SerializedName("status")
    private String status;
    
    @SerializedName("subtest_name")
    private String subtest_name;
    
    @SerializedName("waktu_pengerjaan")
    private int waktu_pengerjaan; // dalam menit
    
    @SerializedName("questions")
    private List<Question> questions;

    public String getStatus() {
        return status;
    }

    public String getSubtestName() {
        return subtest_name;
    }

    public int getWaktuPengerjaan() {
        return waktu_pengerjaan;
    }

    public List<Question> getQuestions() {
        return questions;
    }

    public static class Question {
        @SerializedName("id")
        private String id;
        
        @SerializedName("konteks_soal")
        private String konteks_soal;
        
        @SerializedName("pertanyaan")
        private String pertanyaan;
        
        @SerializedName("pilihan_a")
        private String pilihan_a;
        
        @SerializedName("pilihan_b")
        private String pilihan_b;
        
        @SerializedName("pilihan_c")
        private String pilihan_c;
        
        @SerializedName("pilihan_d")
        private String pilihan_d;
        
        @SerializedName("pilihan_e")
        private String pilihan_e;
        
        @SerializedName("kunci_jawaban")
        private String kunci_jawaban;

        public String getId() {
            return id;
        }

        public String getKonteksSoal() {
            return konteks_soal;
        }

        public String getPertanyaan() {
            return pertanyaan;
        }

        public String getPilihanA() {
            return pilihan_a;
        }

        public String getPilihanB() {
            return pilihan_b;
        }

        public String getPilihanC() {
            return pilihan_c;
        }

        public String getPilihanD() {
            return pilihan_d;
        }

        public String getPilihanE() {
            return pilihan_e;
        }
        
        public String getKunciJawaban() {
            return kunci_jawaban;
        }
    }
}
