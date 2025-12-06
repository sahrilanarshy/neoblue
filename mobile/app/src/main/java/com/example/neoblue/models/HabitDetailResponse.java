package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class HabitDetailResponse {
    @SerializedName("status")
    private String status;

    @SerializedName("message")
    private String message;

    @SerializedName("data")
    private HabitDetailData data;

    public String getStatus() { return status; }
    public String getMessage() { return message; }
    public HabitDetailData getData() { return data; }

    public static class HabitDetailData {
        @SerializedName("id")
        private String id;

        @SerializedName("tanggal")
        private String tanggal;

        @SerializedName("judul")
        private String judul;

        @SerializedName("tipe")
        private String tipe; // 'bacaan' or 'soal'

        @SerializedName("subtest")
        private String subtest; // nama_subtest

        // untuk bacaan
        @SerializedName("isi")
        private String isi;

        // untuk soal
        @SerializedName("soal")
        private List<Soal> soal;

        public String getId() { return id; }
        public String getTanggal() { return tanggal; }
        public String getJudul() { return judul; }
        public String getTipe() { return tipe; }
        public String getSubtest() { return subtest; }
        public String getIsi() { return isi; }
        public List<Soal> getSoal() { return soal; }
    }

    public static class Soal {
        @SerializedName("id")
        private String id;

        @SerializedName("pertanyaan")
        private String pertanyaan;

        @SerializedName("pilihan_a")
        private String pilihanA;

        @SerializedName("pilihan_b")
        private String pilihanB;

        @SerializedName("pilihan_c")
        private String pilihanC;

        @SerializedName("pilihan_d")
        private String pilihanD;

        @SerializedName("pilihan_e")
        private String pilihanE;

        @SerializedName("kunci_jawaban")
        private String kunciJawaban;

        @SerializedName("pembahasan")
        private String pembahasan;

        public String getId() { return id; }
        public String getPertanyaan() { return pertanyaan; }
        public String getPilihanA() { return pilihanA; }
        public String getPilihanB() { return pilihanB; }
        public String getPilihanC() { return pilihanC; }
        public String getPilihanD() { return pilihanD; }
        public String getPilihanE() { return pilihanE; }
        public String getKunciJawaban() { return kunciJawaban; }
        public String getPembahasan() { return pembahasan; }
    }
}
