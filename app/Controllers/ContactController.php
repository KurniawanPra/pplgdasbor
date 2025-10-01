<?php

namespace App\Controllers;

use App\Models\Contact;

class ContactController extends Controller
{
    private Contact $contact;

    public function __construct()
    {
        $this->contact = new Contact();
    }

    public function index(): string
    {
        $page = current_page();
        $perPage = 10;
        $result = $this->contact->paginate($page, $perPage);
        $pagination = build_pagination($result['total'], $perPage, $page);

        return $this->render('dashboard/contact/index', [
            'title' => 'Pesan Masuk',
            'messages' => $result['data'],
            'pagination' => $pagination,
        ], 'dashboard/layout');
    }

    public function submit(): void
    {
        $data = $this->request();
        store_old_input($data);

        $rules = [
            'nama' => 'required|max:100',
            'email' => 'required|email|max:100',
            'pesan' => 'required|min:10',
        ];

        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali formulir kontak.');
            redirect('/#contact');
        }

        if ($this->contact->create($data)) {
            clear_old_input();
            flash('success', 'Terima kasih! Pesan kamu sudah kami terima.');
            app_log('info', 'Pesan kontak masuk', ['email' => $data['email']]);
        } else {
            flash('error', 'Terjadi kesalahan saat mengirim pesan.');
        }

        redirect('/#contact');
    }
}

