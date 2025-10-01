<?php

namespace App\Controllers;

use App\Models\Gallery;

class GalleryController extends Controller
{
    private Gallery $gallery;

    public function __construct()
    {
        $this->gallery = new Gallery();
    }

    public function index(): string
    {
        $page = current_page();
        $perPage = 9;
        $result = $this->gallery->paginate($page, $perPage);
        $pagination = build_pagination($result['total'], $perPage, $page);

        return $this->render('dashboard/gallery/index', [
            'title' => 'Galeri Kegiatan',
            'items' => $result['data'],
            'pagination' => $pagination,
        ], 'dashboard/layout');
    }

    public function upload(): void
    {
        $data = $this->request();
        store_old_input($data);

        $rules = [
            'judul' => 'required|max:120',
            'deskripsi' => 'nullable|max:300',
        ];

        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data galeri.');
            redirect('/dashboard/gallery');
        }

        $result = handle_upload($_FILES['file'] ?? [], 'gallery');
        if ($result['error']) {
            flash('error', $result['error']);
            redirect('/dashboard/gallery');
        }

        if (!$result['path']) {
            flash('error', 'Silakan pilih file gambar.');
            redirect('/dashboard/gallery');
        }

        $description = isset($data['deskripsi']) ? trim($data['deskripsi']) : null;

        $payload = [
            'judul' => $data['judul'],
            'deskripsi' => $description !== '' ? $description : null,
            'file_path' => $result['path'],
            'uploaded_by' => auth_id(),
        ];

        if ($this->gallery->create($payload)) {
            clear_old_input();
            flash('success', 'Gambar berhasil diupload.');
        } else {
            delete_uploaded_file($result['path']);
            flash('error', 'Terjadi kesalahan saat menyimpan data galeri.');
        }

        redirect('/dashboard/gallery');
    }

    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $gallery = $this->gallery->delete($id);
        if ($gallery) {
            delete_uploaded_file($gallery['file_path'] ?? null);
            flash('success', 'Data galeri berhasil dihapus.');
        } else {
            flash('error', 'Gagal menghapus data galeri.');
        }
        redirect('/dashboard/gallery');
    }

    public function apiIndex(): void
    {
        $perPage = (int) ($_GET['limit'] ?? 6);
        if ($perPage <= 0) {
            $perPage = 6;
        }

        $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['default' => 1, 'min_range' => 1]]);
        $result = $this->gallery->paginate($page, $perPage);
        $pagination = build_pagination($result['total'], $perPage, $page);

        $data = array_map(function ($item) {
            $image = $item['file_path'] ? '/uploads.php?path=' . urlencode($item['file_path']) : '/assets/img/avatar-placeholder.png';
            return [
                'id' => (int) $item['id'],
                'judul' => $item['judul'],
                'deskripsi' => $item['deskripsi'] ?? null,
                'file_path' => $item['file_path'],
                'image_url' => $image,
                'created_at' => $item['created_at'],
            ];
        }, $result['data']);

        header('Content-Type: application/json');
        echo json_encode([
            'data' => $data,
            'pagination' => $pagination,
        ]);
        exit;
    }
}
