<?php

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\AnggotaController;
use App\Controllers\AnnouncementController;
use App\Controllers\AttendanceController;
use App\Controllers\ContactController;
use App\Controllers\GalleryController;
use App\Controllers\PiketController;
use App\Controllers\PrestasiController;
use App\Controllers\RosterController;
use App\Controllers\SettingsController;
use App\Controllers\SppController;
use App\Controllers\StrukturController;
use App\Controllers\UsersController;
use App\Controllers\WeeklyTaskController;

return [
    ['GET', '/', [AdminController::class, 'landing']],
    ['GET', '/login', [AuthController::class, 'showLogin']],
    ['POST', '/login/anggota', [AuthController::class, 'loginAnggota'], ['csrf']],
    ['POST', '/login/pengurus', [AuthController::class, 'loginPengurus'], ['csrf']],
    ['GET', '/logout', [AuthController::class, 'logout'], ['auth']],

    ['POST', '/admin/login', [AuthController::class, 'adminLogin'], ['csrf']],

    ['GET', '/dashboard', [AdminController::class, 'dashboard'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus,anggota']],

    ['GET', '/dashboard/pengumuman', [AnnouncementController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],
    ['POST', '/dashboard/pengumuman/store', [AnnouncementController::class, 'store'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],
    ['POST', '/dashboard/pengumuman/delete', [AnnouncementController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],
    ['GET', '/dashboard/pengumuman/feed', [AnnouncementController::class, 'feed'], ['auth']],

    ['GET', '/dashboard/absensi', [AttendanceController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],
    ['POST', '/dashboard/absensi/store', [AttendanceController::class, 'store'], ['auth', 'role:administrator,superadmin,wali_kelas,sekretaris', 'csrf']],
    ['POST', '/dashboard/absensi/delete', [AttendanceController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas,sekretaris', 'csrf']],
    ['GET', '/dashboard/absensi/me', [AttendanceController::class, 'member'], ['auth', 'role:anggota']],

    ['GET', '/dashboard/spp', [SppController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],
    ['POST', '/dashboard/spp/store', [SppController::class, 'store'], ['auth', 'role:administrator,superadmin,wali_kelas,bendahara', 'csrf']],
    ['POST', '/dashboard/spp/delete', [SppController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas,bendahara', 'csrf']],
    ['GET', '/dashboard/spp/me', [SppController::class, 'member'], ['auth', 'role:anggota']],

    ['GET', '/dashboard/tugas', [WeeklyTaskController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus,anggota']],
    ['POST', '/dashboard/tugas/store', [WeeklyTaskController::class, 'store'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],
    ['POST', '/dashboard/tugas/delete', [WeeklyTaskController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],

    ['GET', '/dashboard/anggota', [AnggotaController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas']],
    ['GET', '/dashboard/anggota/create', [AnggotaController::class, 'create'], ['auth', 'role:administrator,superadmin,wali_kelas']],
    ['POST', '/dashboard/anggota/store', [AnggotaController::class, 'store'], ['auth', 'role:administrator,superadmin,wali_kelas', 'csrf']],
    ['GET', '/dashboard/anggota/edit', [AnggotaController::class, 'edit'], ['auth', 'role:administrator,superadmin,wali_kelas']],
    ['POST', '/dashboard/anggota/update', [AnggotaController::class, 'update'], ['auth', 'role:administrator,superadmin,wali_kelas', 'csrf']],
    ['POST', '/dashboard/anggota/delete', [AnggotaController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas', 'csrf']],

    ['GET', '/dashboard/struktur', [StrukturController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas']],
    ['POST', '/dashboard/struktur/store', [StrukturController::class, 'store'], ['auth', 'role:administrator,superadmin,wali_kelas', 'csrf']],
    ['POST', '/dashboard/struktur/delete', [StrukturController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas', 'csrf']],

    ['GET', '/dashboard/piket', [PiketController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],
    ['POST', '/dashboard/piket/store', [PiketController::class, 'store'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],
    ['POST', '/dashboard/piket/delete', [PiketController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],

    ['GET', '/dashboard/roster', [RosterController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],
    ['POST', '/dashboard/roster/store', [RosterController::class, 'store'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],
    ['POST', '/dashboard/roster/delete', [RosterController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],

    ['GET', '/dashboard/gallery', [GalleryController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],
    ['POST', '/dashboard/gallery/upload', [GalleryController::class, 'upload'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],
    ['POST', '/dashboard/gallery/delete', [GalleryController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],

    ['GET', '/dashboard/prestasi', [PrestasiController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],
    ['GET', '/dashboard/prestasi/create', [PrestasiController::class, 'create'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],
    ['POST', '/dashboard/prestasi/store', [PrestasiController::class, 'store'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],
    ['GET', '/dashboard/prestasi/edit', [PrestasiController::class, 'edit'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],
    ['POST', '/dashboard/prestasi/update', [PrestasiController::class, 'update'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],
    ['POST', '/dashboard/prestasi/delete', [PrestasiController::class, 'delete'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus', 'csrf']],

    ['GET', '/dashboard/pesan', [ContactController::class, 'index'], ['auth', 'role:administrator,superadmin,wali_kelas,ketua,wakil_ketua,bendahara,sekretaris,pengurus']],

    ['GET', '/dashboard/users', [UsersController::class, 'index'], ['auth', 'role:administrator,superadmin']],
    ['GET', '/dashboard/users/create', [UsersController::class, 'create'], ['auth', 'role:administrator,superadmin']],
    ['POST', '/dashboard/users/store', [UsersController::class, 'store'], ['auth', 'role:administrator,superadmin', 'csrf']],
    ['GET', '/dashboard/users/edit', [UsersController::class, 'edit'], ['auth', 'role:administrator,superadmin']],
    ['POST', '/dashboard/users/update', [UsersController::class, 'update'], ['auth', 'role:administrator,superadmin', 'csrf']],
    ['POST', '/dashboard/users/delete', [UsersController::class, 'delete'], ['auth', 'role:administrator,superadmin', 'csrf']],

    ['GET', '/dashboard/settings', [SettingsController::class, 'index'], ['auth', 'role:administrator,superadmin']],
    ['POST', '/dashboard/settings/update', [SettingsController::class, 'update'], ['auth', 'role:administrator,superadmin', 'csrf']],

    ['POST', '/contact/submit', [ContactController::class, 'submit'], ['csrf']],

    ['GET', '/api/anggota', [AnggotaController::class, 'apiIndex']],
    ['GET', '/api/gallery', [GalleryController::class, 'apiIndex']],
    ['GET', '/system/super/login', [AuthController::class, 'showHiddenAdminLogin']],
];







