package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;

public class SubmitTryoutResponse {

    @SerializedName("status")
    private String status;

    @SerializedName("message")
    private String message;

    @SerializedName("session_id")
    private int sessionId;

    public String getStatus() {
        return status;
    }

    public String getMessage() {
        return message;
    }

    public int getSessionId() {
        return sessionId;
    }
}
