package com.example.neoblue;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.Uri;
import android.os.Bundle;
import android.text.Html;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.webkit.WebChromeClient;
import android.webkit.WebSettings;
import android.webkit.WebView;
import android.webkit.WebViewClient;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.WindowCompat;

import com.example.neoblue.api.ApiConfig;
import com.example.neoblue.api.ApiService;
import com.example.neoblue.models.IsiMateriResponse;
import com.google.android.material.bottomnavigation.BottomNavigationView;

import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class IsiMateriActivity extends AppCompatActivity {

    private WebView webViewYoutube;
    private TextView tvJudulMateri, tvIsiMateri;
    private LinearLayout videoErrorContainer;
    private Button btnOpenYoutubeError, btnOpenYoutubeAlways;
    private String materiId;
    private String videoUrl = "";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        WindowCompat.setDecorFitsSystemWindows(getWindow(), false);
        setContentView(R.layout.activity_isi_materi);

        setupHeader();

        materiId = getIntent().getStringExtra("MATERI_ID");
        if (materiId == null) {
            Toast.makeText(this, "ID Materi tidak ditemukan", Toast.LENGTH_SHORT).show();
            finish();
            return;
        }

        // Initialize UI
        webViewYoutube = findViewById(R.id.webViewYoutube);
        tvJudulMateri = findViewById(R.id.tv_judul_materi);
        tvIsiMateri = findViewById(R.id.tv_isi_materi);
        videoErrorContainer = findViewById(R.id.video_error_container);
        btnOpenYoutubeError = findViewById(R.id.btn_open_youtube_error);
        btnOpenYoutubeAlways = findViewById(R.id.btn_open_youtube_always);
        ImageView btnBack = findViewById(R.id.btn_back_materi);

        // Setup WebView for YouTube
        setupWebView();

        // Back Button
        btnBack.setOnClickListener(v -> finish());

        // Open YouTube App Button Actions
        View.OnClickListener openYoutubeListener = v -> {
            if (!videoUrl.isEmpty()) {
                Intent intent = new Intent(Intent.ACTION_VIEW, Uri.parse(videoUrl));
                intent.addFlags(Intent.FLAG_ACTIVITY_NEW_TASK);
                intent.setPackage("com.google.android.youtube");
                try {
                    startActivity(intent);
                } catch (Exception e) {
                    // If YouTube app not installed, open in browser
                    intent.setPackage(null);
                    startActivity(intent);
                }
            } else {
                Toast.makeText(IsiMateriActivity.this, "Link video tidak tersedia", Toast.LENGTH_SHORT).show();
            }
        };

        btnOpenYoutubeError.setOnClickListener(openYoutubeListener);
        btnOpenYoutubeAlways.setOnClickListener(openYoutubeListener);

        // Load Data
        loadMateriDetail();

        // Setup Bottom Nav
        setupBottomNav();
    }

    private void setupHeader() {
        ImageView btnNotif = findViewById(R.id.btn_notifikasi);
        if (btnNotif != null) {
            btnNotif.setOnClickListener(v -> startActivity(new Intent(IsiMateriActivity.this, NotifikasiActivity.class)));
        }

        ImageView btnProfil = findViewById(R.id.btn_profil);
        if (btnProfil != null) {
            btnProfil.setOnClickListener(v -> startActivity(new Intent(IsiMateriActivity.this, ProfilActivity.class)));
        }
    }

    private void setupWebView() {
        WebSettings webSettings = webViewYoutube.getSettings();
        webSettings.setJavaScriptEnabled(true);
        webSettings.setDomStorageEnabled(true);
        webSettings.setSupportZoom(false);
        webSettings.setBuiltInZoomControls(false);
        webSettings.setDisplayZoomControls(false);
        
        // User Agent untuk menghindari pemblokiran beberapa embedded video
        webSettings.setUserAgentString("Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Mobile Safari/537.36");

        webViewYoutube.setWebChromeClient(new WebChromeClient());
        webViewYoutube.setWebViewClient(new WebViewClient() {
             @Override
             public boolean shouldOverrideUrlLoading(WebView view, String url) {
                 return false;
             }
        });
    }

    private void loadMateriDetail() {
        SharedPreferences sharedPreferences = getSharedPreferences("user_session", Context.MODE_PRIVATE);
        String userId = sharedPreferences.getString("user_id", "");

        ApiService apiService = ApiConfig.getApiService();
        // Menggunakan getIsiMateri sesuai di ApiService
        Call<IsiMateriResponse> call = apiService.getIsiMateri(materiId, userId);

        call.enqueue(new Callback<IsiMateriResponse>() {
            @Override
            public void onResponse(Call<IsiMateriResponse> call, Response<IsiMateriResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    // Menggunakan tipe data inner class dari IsiMateriResponse
                    IsiMateriResponse.IsiMateriData data = response.body().getData();
                    if (data != null) {
                        // Sesuaikan nama getter dengan IsiMateriResponse.java
                        tvJudulMateri.setText(data.getJudul());
                        
                        // Set Isi Materi (HTML support)
                        // Menggunakan getDeskripsi() karena field 'isi_materi' biasanya di-map ke deskripsi di model ini
                        if (data.getDeskripsi() != null) {
                            tvIsiMateri.setText(Html.fromHtml(data.getDeskripsi(), Html.FROM_HTML_MODE_COMPACT));
                        } else {
                            tvIsiMateri.setText("Belum ada ringkasan materi.");
                        }

                        // Load Video if exists
                        videoUrl = data.getVideoUrl();
                        if (videoUrl != null && !videoUrl.isEmpty()) {
                            String embedUrl = getEmbedUrl(videoUrl);
                            if (embedUrl != null) {
                                String html = "<iframe width=\"100%\" height=\"100%\" src=\"" + embedUrl + "\" frameborder=\"0\" allowfullscreen></iframe>";
                                webViewYoutube.loadData(html, "text/html", "utf-8");
                                webViewYoutube.setVisibility(View.VISIBLE);
                                videoErrorContainer.setVisibility(View.GONE);
                            } else {
                                // Fallback if not embeddable youtube link
                                webViewYoutube.setVisibility(View.GONE);
                                videoErrorContainer.setVisibility(View.VISIBLE);
                            }
                        } else {
                            webViewYoutube.setVisibility(View.GONE);
                            videoErrorContainer.setVisibility(View.GONE); // No video at all
                            btnOpenYoutubeAlways.setVisibility(View.GONE);
                        }
                    }
                } else {
                    Toast.makeText(IsiMateriActivity.this, "Gagal memuat materi", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<IsiMateriResponse> call, Throwable t) {
                Toast.makeText(IsiMateriActivity.this, "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }

    private String getEmbedUrl(String url) {
        String videoId = null;
        if (url.contains("youtube.com/watch?v=")) {
            videoId = url.split("v=")[1];
            int ampersandPosition = videoId.indexOf('&');
            if (ampersandPosition != -1) {
                videoId = videoId.substring(0, ampersandPosition);
            }
        } else if (url.contains("youtu.be/")) {
            videoId = url.split("youtu.be/")[1];
        } else if (url.contains("youtube.com/embed/")) {
            return url; // Already embed link
        }

        if (videoId != null) {
            return "https://www.youtube.com/embed/" + videoId;
        }
        return null;
    }

    private void setupBottomNav() {
        BottomNavigationView bottomNav = findViewById(R.id.bottom_navigation);
        if (bottomNav != null) {
            bottomNav.setSelectedItemId(R.id.nav_materi);
            bottomNav.setOnNavigationItemSelectedListener(item -> {
                int itemId = item.getItemId();
                Intent intent = new Intent(IsiMateriActivity.this, MainActivity.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP | Intent.FLAG_ACTIVITY_NEW_TASK);

                if (itemId == R.id.nav_materi) {
                     startActivity(intent);
                     return true;
                } else if (itemId == R.id.nav_habit) {
                    intent.putExtra("fragment", "habit");
                    startActivity(intent);
                    return true;
                } else if (itemId == R.id.nav_short) {
                    intent.putExtra("fragment", "short");
                    startActivity(intent);
                    return true;
                } else if (itemId == R.id.nav_tryout) {
                    intent.putExtra("fragment", "tryout");
                    startActivity(intent);
                    return true;
                } else if (itemId == R.id.nav_jadwal) {
                    intent.putExtra("fragment", "jadwal");
                    startActivity(intent);
                    return true;
                }
                return false;
            });
        }
    }
}