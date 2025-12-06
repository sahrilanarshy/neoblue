package com.example.neoblue.utils;

import android.content.Context;
import android.content.SharedPreferences;

public class PreferencesManager {
    private static final String PREF_NAME = "neoblue_prefs";

    public static final String KEY_AUTH_TOKEN = "auth_token";
    public static final String KEY_USER_ID = "user_id";
    public static final String KEY_USERNAME = "username";
    public static final String KEY_IS_LOGGED_IN = "is_logged_in";

    private final SharedPreferences prefs;

    public PreferencesManager(Context context) {
        this.prefs = context.getSharedPreferences(PREF_NAME, Context.MODE_PRIVATE);
    }

    // Generic put/get
    public void putString(String key, String value) {
        prefs.edit().putString(key, value).apply();
    }

    public String getString(String key, String defValue) {
        return prefs.getString(key, defValue);
    }

    public void putInt(String key, int value) {
        prefs.edit().putInt(key, value).apply();
    }

    public int getInt(String key, int defValue) {
        return prefs.getInt(key, defValue);
    }

    public void putBoolean(String key, boolean value) {
        prefs.edit().putBoolean(key, value).apply();
    }

    public boolean getBoolean(String key, boolean defValue) {
        return prefs.getBoolean(key, defValue);
    }

    public void remove(String key) {
        prefs.edit().remove(key).apply();
    }

    public void clear() {
        prefs.edit().clear().apply();
    }

    // Convenience methods
    public void saveAuthToken(String token) {
        putString(KEY_AUTH_TOKEN, token);
        putBoolean(KEY_IS_LOGGED_IN, token != null && !token.isEmpty());
    }

    public String getAuthToken() {
        return getString(KEY_AUTH_TOKEN, "");
    }

    public void saveUserId(String userId) {
        putString(KEY_USER_ID, userId);
    }

    public String getUserId() {
        return getString(KEY_USER_ID, "");
    }

    public void saveUsername(String username) {
        putString(KEY_USERNAME, username);
    }

    public String getUsername() {
        return getString(KEY_USERNAME, "");
    }

    public boolean isLoggedIn() {
        return getBoolean(KEY_IS_LOGGED_IN, false);
    }
}
