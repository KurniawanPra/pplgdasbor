<?php

use App\Controllers\AuthController;
use App\Controllers\AdminController;
use App\Controllers\AnggotaController;
use App\Controllers\PiketController;
use App\Controllers\RosterController;
use App\Controllers\StrukturController;
use App\Controllers\GalleryController;
use App\Controllers\PrestasiController;
use App\Controllers\ContactController;
use App\Controllers\UsersController;
use App\Controllers\SettingsController;

return [
    ['GET', '/', [AdminController::class, 'landing']],
    ['GET', '/login', [AuthController::class, 'showLogin']],
    ['POST', '/login', [AuthController::class, 'login'], ['csrf']],
    ['GET', '/logout', [AuthController::class, 'logout'], ['auth']],

    ['GET', '/admin/login', [AuthController::class, 'showAdminLogin']],
    ['POST', '/admin/login', [AuthController::class, 'adminLogin'], ['csrf']],

    ['GET', '/dashboard', [AdminController::class, 'dashboard'], ['auth', 'role:superadmin,pengurus']],

    ['GET', '/dashboard/anggota', [AnggotaController::class, 'index'], ['auth', 'role:superadmin,pengurus']],
    ['GET', '/dashboard/anggota/create', [AnggotaController::class, 'create'], ['auth', 'role:superadmin,pengurus']],
    ['POST', '/dashboard/anggota/store', [AnggotaController::class, 'store'], ['auth', 'role:superadmin,pengurus', 'csrf']],
    ['GET', '/dashboard/anggota/edit', [AnggotaController::class, 'edit'], ['auth', 'role:superadmin,pengurus']],
    ['POST', '/dashboard/anggota/update', [AnggotaController::class, 'update'], ['auth', 'role:superadmin,pengurus', 'csrf']],
    ['POST', '/dashboard/anggota/delete', [AnggotaController::class, 'delete'], ['auth', 'role:superadmin,pengurus', 'csrf']],

    ['GET', '/dashboard/struktur', [StrukturController::class, 'index'], ['auth', 'role:superadmin,pengurus']],
    ['POST', '/dashboard/struktur/store', [StrukturController::class, 'store'], ['auth', 'role:superadmin,pengurus', 'csrf']],
    ['POST', '/dashboard/struktur/delete', [StrukturController::class, 'delete'], ['auth', 'role:superadmin,pengurus', 'csrf']],

    ['GET', '/dashboard/piket', [PiketController::class, 'index'], ['auth', 'role:superadmin,pengurus']],
    ['POST', '/dashboard/piket/store', [PiketController::class, 'store'], ['auth', 'role:superadmin,pengurus', 'csrf']],
    ['POST', '/dashboard/piket/delete', [PiketController::class, 'delete'], ['auth', 'role:superadmin,pengurus', 'csrf']],

    ['GET', '/dashboard/roster', [RosterController::class, 'index'], ['auth', 'role:superadmin,pengurus']],
    ['POST', '/dashboard/roster/store', [RosterController::class, 'store'], ['auth', 'role:superadmin,pengurus', 'csrf']],
    ['POST', '/dashboard/roster/delete', [RosterController::class, 'delete'], ['auth', 'role:superadmin,pengurus', 'csrf']],

    ['GET', '/dashboard/gallery', [GalleryController::class, 'index'], ['auth', 'role:superadmin,pengurus']],
    ['GET', '/dashboard/prestasi', [PrestasiController::class, 'index'], ['auth', 'role:superadmin,pengurus']],
    ['GET', '/dashboard/prestasi/create', [PrestasiController::class, 'create'], ['auth', 'role:superadmin,pengurus']],
    ['POST', '/dashboard/prestasi/store', [PrestasiController::class, 'store'], ['auth', 'role:superadmin,pengurus', 'csrf']],
    ['GET', '/dashboard/prestasi/edit', [PrestasiController::class, 'edit'], ['auth', 'role:superadmin,pengurus']],
    ['POST', '/dashboard/prestasi/update', [PrestasiController::class, 'update'], ['auth', 'role:superadmin,pengurus', 'csrf']],
    ['POST', '/dashboard/prestasi/delete', [PrestasiController::class, 'delete'], ['auth', 'role:superadmin,pengurus', 'csrf']],
    ['GET', '/dashboard/pesan', [ContactController::class, 'index'], ['auth', 'role:superadmin,pengurus']],
    ['POST', '/dashboard/gallery/upload', [GalleryController::class, 'upload'], ['auth', 'role:superadmin,pengurus', 'csrf']],
    ['POST', '/dashboard/gallery/delete', [GalleryController::class, 'delete'], ['auth', 'role:superadmin,pengurus', 'csrf']],

    ['GET', '/dashboard/users', [UsersController::class, 'index'], ['auth', 'role:superadmin']],
    ['GET', '/dashboard/users/create', [UsersController::class, 'create'], ['auth', 'role:superadmin']],
    ['POST', '/dashboard/users/store', [UsersController::class, 'store'], ['auth', 'role:superadmin', 'csrf']],
    ['GET', '/dashboard/users/edit', [UsersController::class, 'edit'], ['auth', 'role:superadmin']],
    ['POST', '/dashboard/users/update', [UsersController::class, 'update'], ['auth', 'role:superadmin', 'csrf']],
    ['POST', '/dashboard/users/delete', [UsersController::class, 'delete'], ['auth', 'role:superadmin', 'csrf']],

    ['GET', '/dashboard/settings', [SettingsController::class, 'index'], ['auth', 'role:superadmin']],
    ['POST', '/dashboard/settings/update', [SettingsController::class, 'update'], ['auth', 'role:superadmin', 'csrf']],

    ['POST', '/contact/submit', [ContactController::class, 'submit'], ['csrf']],

    ['GET', '/api/anggota', [AnggotaController::class, 'apiIndex']],
    ['GET', '/api/gallery', [GalleryController::class, 'apiIndex']],
    ['GET', '/system/super/login', [AuthController::class, 'showHiddenAdminLogin']],
];







