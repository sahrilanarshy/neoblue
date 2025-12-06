package com.example.neoblue.api;

import com.example.neoblue.models.DetailMateriResponse;
import com.example.neoblue.models.HabitDetailResponse;
import com.example.neoblue.models.HabitResponse;
import com.example.neoblue.models.IsiMateriResponse;
import com.example.neoblue.models.JadwalResponse;
import com.example.neoblue.models.MateriTryoutResponse;
import com.example.neoblue.models.MetodePembayaranResponse;
import com.example.neoblue.models.PaketPremiumResponse;
import com.example.neoblue.models.PengumumanResponse;
import com.example.neoblue.models.PremiumDataResponse;
import com.example.neoblue.models.ProfileResponse;
import com.example.neoblue.models.ShortResponse;
import com.example.neoblue.models.SoalTryoutResponse;
import com.example.neoblue.models.SubtestResponse;
import com.example.neoblue.models.SubmitTryoutRequest;
import com.example.neoblue.models.SubmitTryoutResponse;
import com.example.neoblue.models.TryoutResponse;
import com.example.neoblue.models.UpgradePremiumResponse;

import okhttp3.MultipartBody;
import okhttp3.RequestBody;
import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.GET;
import retrofit2.http.HTTP;
import retrofit2.http.Multipart;
import retrofit2.http.POST;
import retrofit2.http.Part;
import retrofit2.http.Query;

public interface ApiService {
    @GET("api_subtest.php")
    Call<SubtestResponse> getSubtests();

    @GET("api_detail_materi.php")
    Call<DetailMateriResponse> getMateriSubtest(@Query("subtest_id") String subtestId);

    @GET("api_isi_materi.php")
    Call<IsiMateriResponse> getIsiMateri(@Query("materi_id") String materiId, @Query("user_id") String userId);

    @GET("api_habit_mobile.php")
    Call<HabitResponse> getHabits(@Query("user_id") String userId);

    @GET("api_habit_detail.php")
    Call<HabitDetailResponse> getHabitDetail(@Query("id") String habitId, @Query("user_id") String userId);

    @FormUrlEncoded
    @POST("api_profil.php")
    Call<ProfileResponse> getProfile(@Field("user_id") String userId);

    @FormUrlEncoded
    @POST("api_update_profil.php")
    Call<ProfileResponse> updateProfile(
            @Field("user_id") String userId,
            @Field("nama") String nama,
            @Field("telepon") String telepon,
            @Field("password_lama") String passwordLama,
            @Field("password_baru") String passwordBaru
    );

    @Multipart
    @POST("api_update_profil.php")
    Call<ProfileResponse> updateProfilePhoto(
            @Part("user_id") RequestBody userId,
            @Part MultipartBody.Part foto
    );

    @GET("api_short.php")
    Call<ShortResponse> getShorts();

    // MENGGUNAKAN api_jadwal_mobile.php AGAR TIDAK TERKENA SESI LOGIN WEB
    @GET("api_jadwal_mobile.php")
    Call<JadwalResponse> getJadwal(@Query("user_id") String userId);

    @POST("api_jadwal_mobile.php")
    Call<JadwalResponse> addJadwal(@Body Object body); 
    
    @HTTP(method = "DELETE", path = "api_jadwal_mobile.php", hasBody = true)
    Call<JadwalResponse> deleteJadwal(@Body Object body);

    // Endpoint ADMIN: Untuk menyetujui pembayaran
    @FormUrlEncoded
    @POST("api_upgradepremium.php")
    Call<UpgradePremiumResponse> upgradePremium(
            @Field("user_id") String userId,
            @Field("tipe_user") String tipeUser,
            @Field("masa_aktif") String masaAktif
    );

    // Endpoint SISWA: Untuk upload bukti bayar dengan field lengkap sesuai DB
    @Multipart
    @POST("api_upload_bukti.php")
    Call<ResponseBody> uploadBuktiBayar(
            @Part("user_id") RequestBody userId,
            @Part("paket_id") RequestBody paketId,
            @Part("metode_pembayaran_id") RequestBody metodeId,
            @Part("catatan") RequestBody catatan,
            @Part MultipartBody.Part buktiBayar
    );
    
    @GET("api_paket_premium.php")
    Call<PaketPremiumResponse> getPaketPremium();

    @GET("api_metode_pembayaran.php")
    Call<MetodePembayaranResponse> getMetodePembayaran();

    @GET("api_premium_data.php")
    Call<PremiumDataResponse> getPremiumData();

    @GET("api_pengumuman.php")
    Call<PengumumanResponse> getPengumuman();
    
        @GET("api_notifikasi.php")
        Call<com.example.neoblue.models.NotificationResponse> getNotifikasi(@Query("user_id") String userId);

        @FormUrlEncoded
        @POST("api_mark_notification_read.php")
        Call<okhttp3.ResponseBody> markNotificationRead(@Field("user_id") String userId, @Field("notif_id") String notifId);

        @FormUrlEncoded
        @POST("api_delete_notification.php")
        Call<okhttp3.ResponseBody> deleteNotification(@Field("user_id") String userId, @Field("notif_id") String notifId);

        @FormUrlEncoded
        @POST("api_hide_pengumuman.php")
        Call<okhttp3.ResponseBody> hidePengumuman(@Field("user_id") String userId, @Field("peng_id") String pengId);

        @FormUrlEncoded
        @POST("api_mark_pengumuman_read.php")
        Call<okhttp3.ResponseBody> markPengumumanRead(@Field("user_id") String userId, @Field("peng_id") String pengId);

    @GET("api_tryout.php") 
    Call<TryoutResponse> getTryouts();

    // Endpoint baru untuk detail materi tryout (daftar subtest)
    @GET("api_materi_tryout.php")
    Call<MateriTryoutResponse> getMateriTryout(@Query("tryout_id") String tryoutId, @Query("user_id") String userId);
    
    // Endpoint untuk mengambil soal tryout
    @GET("api_get_soal_test.php")
    Call<SoalTryoutResponse> getSoalTryout(
        @Query("id_tryout") String id_tryout,
        @Query("subtest_id") String subtest_id
    );

    // Endpoint untuk mengirim jawaban tryout
    @POST("api_submit_tryout.php")
    Call<SubmitTryoutResponse> submitTryout(@Body SubmitTryoutRequest request);
}
