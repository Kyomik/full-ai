<?php

namespace Tests\Feature;

use App\Filament\Pages\Notifications;
use App\Models\User;
use App\Notifications\AdminBroadcastNotification;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AdminBroadcastNotificationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_scheduled_notification_is_dispatched_by_command_to_each_active_admin(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'status' => 'active',
        ]);
        $activeAdmins = User::factory()->count(2)->create([
            'role' => 'agency_admin',
            'status' => 'active',
        ]);
        $inactiveAdmin = User::factory()->create([
            'role' => 'agency_admin',
            'status' => 'inactive',
        ]);
        $scheduledAt = now()->addMinutes(15);
        $expectedRecipientCount = User::query()
            ->where('role', 'agency_admin')
            ->where('status', 'active')
            ->count();

        $superAdmin->notify(new AdminBroadcastNotification(
            title: 'Jadwal Test',
            message: 'Isi notifikasi terjadwal.',
            category: 'information',
            senderName: $superAdmin->name,
            deliveryStatus: 'scheduled',
            scheduledAt: $scheduledAt->toIso8601String(),
        ));

        $scheduledNotification = DB::table('notifications')
            ->where('notifiable_id', $superAdmin->id)
            ->where('type', AdminBroadcastNotification::class)
            ->first();

        $this->assertNotNull($scheduledNotification);
        $this->assertSame('scheduled', json_decode($scheduledNotification->data, true)['delivery_status']);

        $this->travelTo($scheduledAt->copy()->addMinute());
        Artisan::call('notifications:dispatch-scheduled');

        foreach ($activeAdmins as $admin) {
            $this->assertDatabaseHas('notifications', [
                'notifiable_id' => $admin->id,
                'type' => AdminBroadcastNotification::class,
            ]);

            $data = $admin->notifications()->latest()->first()->data;
            $this->assertSame('filament', $data['format']);
            $this->assertSame('Jadwal Test', $data['title']);
            $this->assertSame('sent', $data['delivery_status']);
        }

        $this->assertDatabaseMissing('notifications', [
            'notifiable_id' => $inactiveAdmin->id,
            'type' => AdminBroadcastNotification::class,
        ]);

        $updatedData = json_decode(DB::table('notifications')->where('id', $scheduledNotification->id)->value('data'), true);
        $this->assertSame('sent', $updatedData['delivery_status']);
        $this->assertSame($expectedRecipientCount, $updatedData['recipient_count']);
        $this->assertSame('admin_broadcast_tracking', $updatedData['format']);
        $this->assertSame(0, $superAdmin->notifications()->where('data->format', 'filament')->count());

        $this->travelBack();
    }

    public function test_super_admin_can_send_immediately_from_notifications_page_to_each_active_admin(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'status' => 'active',
        ]);
        $activeAdmins = User::factory()->count(2)->create([
            'role' => 'agency_admin',
            'status' => 'active',
        ]);
        $inactiveAdmin = User::factory()->create([
            'role' => 'agency_admin',
            'status' => 'inactive',
        ]);

        Filament::setCurrentPanel(Filament::getPanel('super'));

        Livewire::actingAs($superAdmin)
            ->test(Notifications::class)
            ->callAction('sendNotification', data: [
                'title' => 'Kirim Langsung Test',
                'category' => 'announcement',
                'message' => 'Isi notifikasi langsung.',
                'delivery_mode' => 'send_now',
            ]);

        foreach ($activeAdmins as $admin) {
            $notification = $admin->notifications()->latest()->first();

            $this->assertNotNull($notification);
            $this->assertSame('filament', $notification->data['format']);
            $this->assertSame('Kirim Langsung Test', $notification->data['title']);
            $this->assertSame('sent', $notification->data['delivery_status']);
        }

        $this->assertCount(0, $inactiveAdmin->notifications);
        $this->assertSame(1, $superAdmin->notifications()->where('type', AdminBroadcastNotification::class)->count());
        $this->assertSame(0, $superAdmin->notifications()->where('data->format', 'filament')->count());
    }

    public function test_scheduled_notification_created_from_notifications_page_reaches_each_active_admin_when_due(): void
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'status' => 'active',
        ]);
        $activeAdmins = User::factory()->count(2)->create([
            'role' => 'agency_admin',
            'status' => 'active',
        ]);
        $scheduledAt = now()->addMinutes(10);

        Filament::setCurrentPanel(Filament::getPanel('super'));

        Livewire::actingAs($superAdmin)
            ->test(Notifications::class)
            ->callAction('sendNotification', data: [
                'title' => 'Jadwal Dari Halaman Test',
                'category' => 'system',
                'message' => 'Isi jadwal dari halaman notifikasi.',
                'delivery_mode' => 'schedule',
                'scheduled_at' => $scheduledAt->toDateTimeString(),
            ]);

        $this->assertSame(0, $activeAdmins->sum(fn (User $admin): int => $admin->notifications()->count()));

        $this->travelTo($scheduledAt->copy()->addMinute());
        Artisan::call('notifications:dispatch-scheduled');

        foreach ($activeAdmins as $admin) {
            $this->assertSame(1, $admin->notifications()->count());
            $this->assertSame('filament', $admin->notifications()->first()->data['format']);
            $this->assertSame('Jadwal Dari Halaman Test', $admin->notifications()->first()->data['title']);
        }

        $this->travelBack();
    }
}
