package com.example.neoblue.models;

import java.util.List;

public class NotificationResponse {
    private String status;
    private List<Notification> data;

    public String getStatus() {
        return status;
    }

    public List<Notification> getData() {
        return data;
    }

    public static class Notification {
        private String id;
        private String title;
        private String message;
        private String link;
        private String is_read;
        private String created_at;
        private String is_global;

        public String getId() { return id; }
        public String getTitle() { return title; }
        public String getMessage() { return message; }
        public String getLink() { return link; }
        public String getIs_read() { return is_read; }
        public String getCreated_at() { return created_at; }
        public String getIs_global() { return is_global; }
    }
}
