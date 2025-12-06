package com.example.neoblue.models;

import java.util.List;

public class TryoutResponse {
    private boolean is_premium;
    private List<Tryout> tryouts;

    public boolean isPremium() {
        return is_premium;
    }

    public List<Tryout> getTryouts() {
        return tryouts;
    }

    public static class Tryout {
        private String id;
        private String nama_tryout;
        private String tanggal_mulai;
        private String tanggal_selesai;
        private String tipe;

        public String getId() {
            return id;
        }

        public String getNamaTryout() {
            return nama_tryout;
        }

        public String getTanggalMulai() {
            return tanggal_mulai;
        }

        public String getTanggalSelesai() {
            return tanggal_selesai;
        }

        public String getTipe() {
            return tipe;
        }
    }
}
