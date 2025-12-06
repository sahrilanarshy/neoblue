package com.example.neoblue.models;

import java.util.List;

public class PremiumDataResponse {
    private String status;
    private Data data;

    public String getStatus() {
        return status;
    }

    public Data getData() {
        return data;
    }

    public static class Data {
        private List<Paket> paket;
        private List<Metode> metode_pembayaran;

        public List<Paket> getPaket() {
            return paket;
        }

        public List<Metode> getMetodePembayaran() {
            return metode_pembayaran;
        }
    }

    public static class Paket {
        private String id;
        private String nama_paket;
        private String harga;
        private String is_unggulan;
        private List<Fitur> fitur;

        public String getId() {
            return id;
        }

        public String getNamaPaket() {
            return nama_paket;
        }

        public String getHarga() {
            return harga;
        }

        public String getIsUnggulan() {
            return is_unggulan;
        }

        public List<Fitur> getFitur() {
            return fitur;
        }
    }

    public static class Fitur {
        private String id;
        private String nama_fitur;

        public String getId() {
            return id;
        }

        public String getNamaFitur() {
            return nama_fitur;
        }
    }

    public static class Metode {
        private String id;
        private String nama_metode;
        private String nomor_rekening;
        private String atas_nama;

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

        @Override
        public String toString() {
            return nama_metode + " (" + nomor_rekening + ") a.n " + atas_nama;
        }
    }
}
