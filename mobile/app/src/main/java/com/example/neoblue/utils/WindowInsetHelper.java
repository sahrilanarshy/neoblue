package com.example.neoblue.utils;

import android.view.View;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowInsetsCompat;

public class WindowInsetHelper {
    /**
     * Apply top system window inset as extra padding to the view.
     * This implementation attempts to use the modern WindowInsetsCompat.Type API,
     * but falls back to getSystemWindowInsetTop() for older AndroidX versions
     * to avoid compile/runtime issues when the newer API isn't available.
     */
    public static void applyStatusBarPadding(View view) {
        if (view == null) return;
        ViewCompat.setOnApplyWindowInsetsListener(view, (v, insets) -> {
            int top = 0;
            if (insets != null) {
                try {
                    top = insets.getSystemWindowInsetTop();
                } catch (Throwable t) {
                    top = 0;
                }
            }

            v.setPadding(v.getPaddingLeft(), v.getPaddingTop() + top, v.getPaddingRight(), v.getPaddingBottom());
            return insets;
        });
        ViewCompat.requestApplyInsets(view);
    }
}
