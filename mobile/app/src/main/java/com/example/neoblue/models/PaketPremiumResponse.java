package com.example.neoblue.models;

import java.util.List;

public class PaketPremiumResponse {
    private String status;
    private List<PaketPremium> data;

    public String getStatus() {
        return status;
    }

    public List<PaketPremium> getData() {
        return data;
    }

    public static class PaketPremium {
        private String id;
        private String nama_paket;
        private String harga;
        private String deskripsi;
        private List<String> fitur;

        public String getId() {
            return id;
        }

        public String getNamaPaket() {
            return nama_paket;
        }

        public String getHarga() {
            return harga;
        }

        public String getDeskripsi() {
            return deskripsi;
        }

        public List<String> getFitur() {
            return fitur;
        }
    }
}
