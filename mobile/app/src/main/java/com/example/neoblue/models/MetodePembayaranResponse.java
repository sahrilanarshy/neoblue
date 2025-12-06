package com.example.neoblue.models;

import java.util.List;

public class MetodePembayaranResponse {
    private String status;
    private List<MetodePembayaran> data;

    public String getStatus() {
        return status;
    }

    public List<MetodePembayaran> getData() {
        return data;
    }

    public static class MetodePembayaran {
        private String id;
        private String nama_metode;
        private String nomor_rekening;
        private String atas_nama;
        private String jenis; // bank / e-wallet

        public String getId() {
            return id;
        }

        public String getNamaMetode() {
            return nama_metode;
        }

        public String getNomorRekening() {
            return nomor_rekening;
        }

        public String getAtasNama() {
            return atas_nama;
        }

        public String getJenis() {
            return jenis;
        }
        
        @Override
        public String toString() {
            return nama_metode + " - " + nomor_rekening;
        }
    }
}
