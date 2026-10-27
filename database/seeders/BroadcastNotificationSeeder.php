<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BroadcastNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::query()->where('role', 'super_admin')->orderBy('id')->first();

        if (! $superAdmin) {
            throw new \RuntimeException('BroadcastNotificationSeeder membutuhkan akun Super Admin yang sudah ada. Seeder tidak membuat pengguna.');
        }

        $recipients = User::query()->where('role', 'agency_admin')->where('status', 'active')->get();
        $messages = [
            ['Pengumuman Agenda Budaya Bulan Ini', 'Jadwal kegiatan budaya bulan ini telah diperbarui. Silakan lihat daftar event di portal.', 'announcement'],
            ['Festival Kuliner Pesisir Palopo', 'Festival kuliner akan menampilkan ragam hidangan dan tradisi masyarakat pesisir.', 'announcement'],
            ['Pendaftaran Pekan Musik Tradisional', 'Pendaftaran peserta Pekan Musik Tradisional telah dibuka untuk komunitas lokal.', 'information'],
            ['Perubahan Lokasi Kegiatan', 'Lokasi salah satu kegiatan budaya telah disesuaikan. Periksa informasi terbaru sebelum hadir.', 'system'],
            ['Pameran Kerajinan Tana Luwu', 'Pameran kerajinan lokal dapat dikunjungi masyarakat pada akhir pekan.', 'announcement'],
            ['Informasi Layanan Portal', 'Portal budaya menyediakan informasi jadwal, lokasi, dan detail kegiatan.', 'information'],
            ['Pengumuman Lomba Fotografi', 'Karya fotografi bertema warisan budaya dapat dikirimkan melalui penyelenggara.', 'announcement'],
            ['Pemeliharaan Sistem Terjadwal', 'Sistem dapat mengalami jeda singkat pada waktu pemeliharaan terjadwal.', 'system'],
            ['Pagelaran Seni di Pusat Kota', 'Masyarakat diundang menyaksikan pagelaran seni tradisi di pusat Kota Palopo.', 'announcement'],
            ['Pembaruan Kalender Budaya', 'Kalender budaya telah memuat beberapa agenda baru untuk bulan mendatang.', 'information'],
            ['Pengumuman Kelas Budaya Terbuka', 'Kelas terbuka mengenal tari dan musik tradisional tersedia bagi masyarakat.', 'announcement'],
            ['Pengingat Kelengkapan Pengajuan', 'Pastikan informasi jadwal, lokasi, dan dokumen pendukung telah diisi lengkap.', 'system'],
            ['Festival Cerita Rakyat Luwu', 'Sesi mendongeng dan pembacaan cerita rakyat akan berlangsung di ruang budaya.', 'announcement'],
            ['Informasi Galeri Budaya', 'Dokumentasi foto dan video kegiatan dapat dilihat melalui halaman galeri.', 'information'],
            ['Pengumuman Pasar Kreatif', 'Pelaku kreatif lokal dapat mengunjungi Pasar Kreatif Batara Guru.', 'announcement'],
            ['Pemberitahuan Akun', 'Gunakan menu Pengaturan Akun untuk memperbarui informasi profil penyelenggara.', 'system'],
            ['Tur Sejarah Kota Palopo', 'Tur sejarah akan mengunjungi beberapa lokasi bersejarah di Kota Palopo.', 'announcement'],
            ['Jadwal Layanan Admin', 'Layanan pengelolaan data tersedia melalui dashboard Admin Penyelenggara.', 'information'],
            ['Pengumuman Pagelaran Tari', 'Sanggar lokal akan menampilkan ragam tari tradisional Tana Luwu.', 'announcement'],
            ['Informasi Akses Portal', 'Portal publik dapat diakses untuk melihat informasi kegiatan budaya terbaru.', 'system'],
        ];

        foreach ($messages as $index => [$title, $message, $category]) {
            $number = $index + 1;
            $sentAt = now()->subDays(20 - $number)->setTime(10 + ($index % 8), ($index * 7) % 60);
            $this->upsertNotification($superAdmin, $number, $title, $message, $category, $sentAt, $recipients->count(), true);

            foreach ($recipients as $recipient) {
                $this->upsertNotification($recipient, $number, $title, $message, $category, $sentAt, $recipients->count(), false);
            }
        }

        $this->command?->info('20 broadcast notifikasi simulasi berhasil disiapkan untuk Super Admin dan Admin aktif.');
    }

    private function upsertNotification(
        User $recipient,
        int $number,
        string $title,
        string $message,
        string $category,
        Carbon $sentAt,
        int $recipientCount,
        bool $tracking,
    ): void {
        $hash = sha1("fictional-broadcast-{$number}-" . ($tracking ? 'tracking' : 'recipient') . "-user-{$recipient->id}");
        $hash[12] = '5';
        $hash[16] = 'a';
        $uuid = substr($hash, 0, 8) . '-' . substr($hash, 8, 4) . '-' . substr($hash, 12, 4) . '-' . substr($hash, 16, 4) . '-' . substr($hash, 20, 12);
        $date = $sentAt->toDateTimeString();
        $sentAtIso = $sentAt->toIso8601String();

        DB::table('notifications')->updateOrInsert(
            ['id' => $uuid],
            [
                'type' => AdminBroadcastNotification::class,
                'notifiable_type' => User::class,
                'notifiable_id' => $recipient->id,
                'data' => json_encode([
                    'format' => $tracking ? 'admin_broadcast_tracking' : 'filament',
                    'title' => 'Demo: ' . $title,
                    'message' => $message,
                    'category' => $category,
                    'sender_name' => 'Super Admin Demo',
                    'delivery_status' => 'sent',
                    'scheduled_at' => null,
                    'sent_at' => $sentAtIso,
                    'recipient_count' => $recipientCount,
                    'failure_message' => null,
                    'created_at' => $sentAtIso,
                ], JSON_THROW_ON_ERROR),
                'read_at' => ($number + (int) $recipient->id) % 3 === 0 ? $date : null,
                'created_at' => $date,
                'updated_at' => $date,
            ],
        );
    }
}
