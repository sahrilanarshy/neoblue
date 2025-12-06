package com.example.neoblue.utils;

import android.app.Activity;
import android.content.SharedPreferences;
import android.view.View;
import android.widget.TextView;

public class NotificationUtils {
    public static void updateBadgeFromPrefs(Activity activity) {
        SharedPreferences prefs = activity.getSharedPreferences("user_session", activity.MODE_PRIVATE);
        int cnt = prefs.getInt("notif_unread_count", 0);
        View badge = activity.findViewById(activity.getResources().getIdentifier("tv_notif_badge", "id", activity.getPackageName()));
        if (badge instanceof TextView) {
            TextView tv = (TextView) badge;
            if (cnt > 0) {
                tv.setText(String.valueOf(cnt > 99 ? 99 : cnt));
                tv.setVisibility(View.VISIBLE);
            } else {
                tv.setVisibility(View.GONE);
            }
        }
    }
}
