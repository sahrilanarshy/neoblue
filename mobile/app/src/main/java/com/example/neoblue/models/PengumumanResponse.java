package com.example.neoblue.models;

import java.util.List;

public class PengumumanResponse {
    private String status;
    private List<Pengumuman> data;

    public String getStatus() {
        return status;
    }

    public List<Pengumuman> getData() {
        return data;
    }

    public static class Pengumuman {
        private String id;
        private String judul;
        private String isi;
        private String link;
        private String tanggal_terbit;

        public String getId() {
            return id;
        }

        public String getJudul() {
            return judul;
        }

        public String getIsi() {
            return isi;
        }

        public String getLink() {
            return link;
        }

        public String getTanggalTerbit() {
            return tanggal_terbit;
        }
    }
}
