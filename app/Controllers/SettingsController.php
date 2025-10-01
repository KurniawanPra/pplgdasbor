<?php

namespace App\Controllers;

class SettingsController extends Controller
{
    private string $settingsFile;

    public function __construct()
    {
        $this->settingsFile = BASE_PATH . '/storage/settings.json';
    }

    public function index(): string
    {
        return $this->render('dashboard/settings/index', [
            'title' => 'Pengaturan Situs',
            'settings' => $this->getSettings(),
        ], 'dashboard/layout');
    }

    public function update(): void
    {
        $data = $this->request();
        store_old_input($data);

        $rules = [
            'motto' => 'required|max:150',
            'about' => 'required|min:10',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|phone',
            'instagram' => 'nullable|max:100',
        ];

        $errors = validate($data, $rules);
        if ($errors) {
            flash('errors', $errors);
            flash('error', 'Periksa kembali data pengaturan.');
            redirect('/dashboard/settings');
        }

        $settings = [
            'motto' => $data['motto'],
            'about' => $data['about'],
            'contact_email' => $data['contact_email'],
            'contact_phone' => $data['contact_phone'],
            'instagram' => $data['instagram'] ?? '',
        ];

        if (!is_dir(dirname($this->settingsFile))) {
            mkdir(dirname($this->settingsFile), 0775, true);
        }

        file_put_contents($this->settingsFile, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        clear_old_input();
        flash('success', 'Pengaturan berhasil disimpan.');
        redirect('/dashboard/settings');
    }

    private function getSettings(): array
    {
        if (!file_exists($this->settingsFile)) {
            return [
                'motto' => 'Belajar. Berkarya. Berprestasi.',
                'about' => 'Kami adalah kelas XI PPLG yang siap melahirkan talenta digital unggulan.',
                'contact_email' => 'xi1pplg@sekolah.sch.id',
                'contact_phone' => '081234567890',
                'instagram' => '@xi1pplg.aw2',
            ];
        }

        $content = file_get_contents($this->settingsFile);
        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            return [];
        }

        return $decoded;
    }
}
