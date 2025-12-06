package com.example.neoblue.api;

import android.util.Log;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;

import java.util.concurrent.TimeUnit;

import okhttp3.Interceptor;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.Response;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.converter.gson.GsonConverterFactory;

public class ApiConfig {
    // =============================================================================================
    // KONFIGURASI IP SERVER
    // =============================================================================================
    
    // Untuk menjalankan di HOSTING LIVE:
    
    // Untuk menjalankan di HP ASLI (via USB Debugging), gunakan IP Laptop Anda:
    //public static final String BASE_URL = "https://neoblue.mhcloud.web.id/api/";
    public static final String BASE_URL = "http://192.168.18.9/webneoblue/api/";
    // Untuk menjalankan di EMULATOR Android Studio, gunakan IP loopback emulator:
     //public static final String BASE_URL = "http://10.0.2.2/webneoblue/api/";

    public static final int TIMEOUT = 60000; // Naikkan timeout jadi 60 detik untuk upload

    public static ApiService getApiService() {
        // Tambahkan Logger untuk melihat detail Request & Response di Logcat
        HttpLoggingInterceptor logging = new HttpLoggingInterceptor(new HttpLoggingInterceptor.Logger() {
            @Override
            public void log(String message) {
                Log.d("API_LOG", message);
            }
        });
        logging.setLevel(HttpLoggingInterceptor.Level.BODY);

        OkHttpClient client = new OkHttpClient.Builder()
                .connectTimeout(TIMEOUT, TimeUnit.MILLISECONDS)
                .readTimeout(TIMEOUT, TimeUnit.MILLISECONDS)
                .writeTimeout(TIMEOUT, TimeUnit.MILLISECONDS) // Tambahkan write timeout
                .addInterceptor(logging) // Pasang logger
                .addInterceptor(new Interceptor() {
                    @Override
                    public Response intercept(Chain chain) {
                        Request original = chain.request();
                        // Menyederhanakan Request Header agar tidak diblokir Hosting
                        Request request = original.newBuilder()
                                // Menyamar sebagai Browser Chrome agar lolos dari 'aes.js' security check
                                .header("User-Agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36")
                                .header("Accept", "application/json")
                                .build();
                        try {
                            return chain.proceed(request);
                        } catch (Exception e) {
                            throw new RuntimeException(e);
                        }
                    }
                })
                .build();

        Gson gson = new GsonBuilder()
                .setLenient()
                .create();

        Retrofit retrofit = new Retrofit.Builder()
                .baseUrl(BASE_URL)
                .client(client)
                .addConverterFactory(GsonConverterFactory.create(gson))
                .build();

        return retrofit.create(ApiService.class);
    }
}