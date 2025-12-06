package com.example.neoblue;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.ViewCompat;
import androidx.core.view.WindowCompat;
import androidx.core.view.WindowInsetsCompat;
import androidx.fragment.app.Fragment;

import android.content.Intent;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.RelativeLayout;

import com.google.android.material.bottomnavigation.BottomNavigationView;

public class MainActivity extends AppCompatActivity {

    private BottomNavigationView bottomNav;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        WindowCompat.setDecorFitsSystemWindows(getWindow(), false);
        setContentView(R.layout.activity_main);

        // Setup Header Click Listeners
        ImageView btnNotif = findViewById(R.id.btn_notifikasi);
        if (btnNotif != null) {
            btnNotif.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    startActivity(new Intent(MainActivity.this, NotifikasiActivity.class));
                }
            });
        }

        ImageView btnProfil = findViewById(R.id.btn_profil);
        if (btnProfil != null) {
            btnProfil.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    startActivity(new Intent(MainActivity.this, ProfilActivity.class));
                }
            });
        }

        bottomNav = findViewById(R.id.bottom_navigation);
        bottomNav.setOnNavigationItemSelectedListener(navListener);

        // Handle Fragment Selection from Intent
        if (savedInstanceState == null) {
            String fragmentTag = getIntent().getStringExtra("fragment");
            if (fragmentTag != null) {
                switch (fragmentTag) {
                    case "habit":
                        bottomNav.setSelectedItemId(R.id.nav_habit);
                        break;
                    case "short":
                        bottomNav.setSelectedItemId(R.id.nav_short);
                        break;
                    case "tryout":
                        bottomNav.setSelectedItemId(R.id.nav_tryout);
                        break;
                    case "jadwal":
                        bottomNav.setSelectedItemId(R.id.nav_jadwal);
                        break;
                    default:
                        getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container,
                                new HomeFragment()).commit();
                        break;
                }
            } else {
                getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container,
                        new HomeFragment()).commit();
            }
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        com.example.neoblue.utils.NotificationUtils.updateBadgeFromPrefs(this);
    }

    private BottomNavigationView.OnNavigationItemSelectedListener navListener =
            new BottomNavigationView.OnNavigationItemSelectedListener() {
                @Override
                public boolean onNavigationItemSelected(@NonNull MenuItem item) {
                    Fragment selectedFragment = null;

                    int itemId = item.getItemId();

                    if (itemId == R.id.nav_materi) {
                        selectedFragment = new HomeFragment();
                    } else if (itemId == R.id.nav_habit) {
                        selectedFragment = new HabitFragment();
                    } else if (itemId == R.id.nav_short) {
                        selectedFragment = new ShortFragment();
                    } else if (itemId == R.id.nav_tryout) {
                        selectedFragment = new TryoutFragment();
                    } else if (itemId == R.id.nav_jadwal) {
                        selectedFragment = new JadwalFragment();
                    }

                    if (selectedFragment != null) {
                        getSupportFragmentManager().beginTransaction().replace(R.id.fragment_container,
                                selectedFragment).commit();
                    }

                    return true;
                }
            };
}