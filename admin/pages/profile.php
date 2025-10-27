    <style>
        .profile-card-left {
            text-align: center;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 1rem auto;
        }

        .profile-card-left h5 {
            font-weight: 600;
        }

        .profile-card-left .text-muted {
            font-size: 0.9rem;
        }

        .profile-info-container {
            text-align: left;
            padding-top: 1.5rem;
        }

        .profile-info-container .form-control[readonly] {
            background-color: #fff;
            border: none;
            padding-left: 0;
            font-size: 0.95rem;
            color: #495057;
        }

        .profile-info-container label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
        }

        .profile-info-container textarea[readonly] {
            resize: none;
        }

        .form-label {
            font-weight: 500;
            color: #495057;
        }
    </style>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>NeoBlue | Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Profile</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-home">
                    <a href="#">
                        <i class="icon-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Profile</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card profile-card-left">
                    <div class="card-body">
                        <img src="assets/img/logo/icon profile.png" alt="Avatar" class="profile-avatar">
                        <h5 class="card-title">Sahril Sidik</h5>
                        <p class="text-muted">Administrator</p>
                    </div>
                    <div class="card-body profile-info-container">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="admin@example.com" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Telp</label>
                            <input type="number" class="form-control" value="083119897273" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" value="************" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <h5 class="mb-3">Edit Profile</h5>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="fullName"
                                        placeholder="Sahril Sidik">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email"
                                        placeholder="sahrilwfc@gmail.com">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <label for="noTelp" class="form-label">No Telp</label>
                                    <input type="text" class="form-control" id="noTelp"
                                        placeholder="083119897273">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <label for="foto_upload" class="form-label">Upload Foto</label>
                                    <input type="file" class="form-control" id="foto_upload" name="foto_upload"
                                        accept="image/png, image/jpeg" />
                                    <small class="form-text text-muted">Pilih file gambar dari komputer Anda (format
                                        .jpg, .png).</small>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <label for="password" class="form-label">Password Baru (opsional)</label>
                                    <input type="password" class="form-control" id="password"
                                        placeholder="Isi jika ingin mengubah password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 text-end">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
