package com.example.neoblue.models;

import com.google.gson.annotations.SerializedName;
import java.util.List;

public class SubmitTryoutRequest {

    @SerializedName("user_id")
    private String userId;

    @SerializedName("id_tryout")
    private String idTryout;

    @SerializedName("subtest_id")
    private String subtestId;

    @SerializedName("user_answers")
    private List<String> userAnswers;

    public SubmitTryoutRequest(String userId, String idTryout, String subtestId, List<String> userAnswers) {
        this.userId = userId;
        this.idTryout = idTryout;
        this.subtestId = subtestId;
        this.userAnswers = userAnswers;
    }
}
