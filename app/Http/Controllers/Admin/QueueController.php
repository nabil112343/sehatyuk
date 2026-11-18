<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $q = Ticket::with('doctor.poli', 'user')->orderBy('visit_date')->orderBy('queue_number');

        if ($request->filled('date')) {
            $q->where('visit_date', $request->date);
        }
        if ($request->filled('doctor_id')) {
            $q->where('doctor_id', $request->doctor_id);
        }

        return $q->get()->map(fn($t) => $this->row($t))->values();
    }

    /** Panggil pasien "menunggu" berikutnya (hari ini) untuk dokter tertentu. */
    public function next(Request $request)
    {
        $request->validate(['doctor_id' => 'required|exists:doctors,id']);

        $ticket = Ticket::where('doctor_id', $request->doctor_id)
            ->where('visit_date', today()->toDateString())
            ->where('status', 'waiting')
            ->orderBy('queue_number')->first();

        abort_if(!$ticket, 404, 'Tidak ada pasien yang menunggu hari ini.');

        return $this->action($ticket, 'call');
    }

    /** call | start | finish | skip | notify */
    public function action(Ticket $ticket, string $action)
    {
        $scope = fn() => Ticket::where('doctor_id', $ticket->doctor_id)
            ->where('visit_date', $ticket->visit_date->toDateString());

        switch ($action) {
            case 'call': // memanggil pasien (menunggu / terlewat yang akhirnya datang)
                abort_unless(in_array($ticket->status, ['waiting', 'skipped']), 422, 'Tiket ini tidak dapat dipanggil.');
                abort_if($scope()->where('status', 'examining')->where('id', '!=', $ticket->id)->exists(),
                    422, 'Masih ada pasien yang sedang diperiksa. Selesaikan dulu.');
                // Pasien yang sebelumnya dipanggil tapi belum datang -> otomatis terlewat
                $scope()->where('status', 'called')->where('id', '!=', $ticket->id)->update(['status' => 'skipped']);
                $ticket->update(['status' => 'called', 'called_at' => now()]);
                break;

            case 'start':
                abort_unless($ticket->status === 'called', 422, 'Pasien belum dipanggil.');
                $ticket->update(['status' => 'examining']);
                break;

            case 'finish':
                abort_unless(in_array($ticket->status, ['called', 'examining']), 422, 'Pasien belum diperiksa.');
                $ticket->update(['status' => 'done']);
                break;

            case 'skip': // pasien telat -> lanjut ke pasien berikutnya
                abort_unless(in_array($ticket->status, ['waiting', 'called']), 422, 'Tiket tidak dapat dilewati.');
                $ticket->update(['status' => 'skipped']);
                break;

            case 'notify': // tandai sudah dikirimi WA
                $ticket->update(['notified_at' => now()]);
                break;

            default:
                abort(404);
        }

        return $this->row($ticket->fresh(['doctor.poli', 'user']));
    }

    private function row(Ticket $t): array
    {
        $a = $t->toApi();
        $a['notified'] = (bool) $t->notified_at;
        $a['wa_url'] = $this->waUrl($a);
        return $a;
    }

    private function waUrl(array $a): string
    {
        $phone = preg_replace('/\D/', '', (string) $a['phone']);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        $name = $a['patient'];
        $code = $a['queue_code'];
        $doc = $a['doctor'];

        if ($a['status'] === 'skipped') {
            $msg = "Halo $name, nomor antrean $code ($doc) di SehatYuk Klinik sudah terlewat. Silakan segera datang dan lapor ke petugas agar dilayani setelah pasien yang sedang diperiksa.";
        } elseif ($a['ahead'] <= 0) {
            $msg = "Halo $name, giliran Anda (nomor $code, $doc) di SehatYuk Klinik akan segera tiba. Mohon segera menuju ruang tunggu. Terima kasih.";
        } else {
            $msg = "Halo $name, nomor antrean $code ($doc) di SehatYuk Klinik tinggal {$a['ahead']} pasien lagi sebelum giliran Anda. Mohon segera datang ke klinik. Jika terlambat, antrean akan dilanjutkan ke pasien berikutnya. Terima kasih.";
        }

        return 'https://wa.me/' . $phone . '?text=' . rawurlencode($msg);
    }
}
