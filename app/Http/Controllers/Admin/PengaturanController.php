<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PengaturanController extends Controller
{
    public function index()
    {
        $pengaturan = Pengaturan::orderBy('grup')
            ->orderBy('key')
            ->get();

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => ['required', 'string', 'max:255', 'unique:pengaturan,key'],
            'label' => ['required', 'string', 'max:255'],
            'tipe' => ['required', 'in:string,integer,decimal,boolean,json,time,date'],
            'grup' => ['nullable', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
            'value' => ['nullable', 'string'],
        ]);

        $pengaturan = new Pengaturan();
        $pengaturan->key = $request->input('key');
        $pengaturan->label = $request->input('label');
        $pengaturan->tipe = $request->input('tipe');
        $pengaturan->grup = $request->input('grup');
        $pengaturan->keterangan = $request->input('keterangan');
        $pengaturan->value = $this->normalizeValueByType($request->input('tipe'), $request->input('value'));
        $pengaturan->save();

        return redirect()
            ->route('admin.pengaturan')
            ->with('success', 'Pengaturan baru berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $pengaturan = Pengaturan::findOrFail($id);

        session()->flash('edit_pengaturan_id', $pengaturan->id);

        $request->validate([
            'value' => ['nullable', 'string'],
        ]);

        $pengaturan->value = $this->normalizeValueByType($pengaturan->tipe, $request->input('value'));
        $pengaturan->save();

        return redirect()
            ->route('admin.pengaturan')
            ->with('success', 'Pengaturan berhasil diperbarui.');
    }

    private function normalizeValueByType(string $tipe, mixed $value): ?string
    {
        if ($value === null || $value === '') {
            if ($tipe === 'boolean') {
                return '0';
            }

            return null;
        }

        return match ($tipe) {
            'integer' => $this->normalizeInteger($value),
            'decimal' => $this->normalizeDecimal($value),
            'boolean' => $this->normalizeBoolean($value),
            'json' => $this->normalizeJson($value),
            'time' => $this->normalizeTime($value),
            'date' => $this->normalizeDate($value),
            default => trim((string) $value),
        };
    }

    private function normalizeInteger(mixed $value): string
    {
        if (filter_var($value, FILTER_VALIDATE_INT) === false) {
            throw ValidationException::withMessages([
                'value' => 'Nilai harus berupa angka bulat.',
            ]);
        }

        return (string) $value;
    }

    private function normalizeDecimal(mixed $value): string
    {
        $normalized = str_replace(',', '.', trim((string) $value));

        if (! is_numeric($normalized)) {
            throw ValidationException::withMessages([
                'value' => 'Nilai harus berupa angka desimal.',
            ]);
        }

        return (string) $normalized;
    }

    private function normalizeBoolean(mixed $value): string
    {
        $normalized = strtolower(trim((string) $value));

        if (in_array($normalized, ['1', 'true', 'ya', 'yes'], true)) {
            return '1';
        }

        if (in_array($normalized, ['0', 'false', 'tidak', 'no'], true)) {
            return '0';
        }

        throw ValidationException::withMessages([
            'value' => 'Nilai boolean harus berupa ya/tidak atau true/false.',
        ]);
    }

    private function normalizeJson(mixed $value): string
    {
        json_decode((string) $value, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw ValidationException::withMessages([
                'value' => 'Format JSON tidak valid.',
            ]);
        }

        return json_encode(json_decode((string) $value, true), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    private function normalizeTime(mixed $value): string
    {
        $normalized = trim((string) $value);

        if (! preg_match('/^(2[0-3]|[01]?\d):([0-5]\d)$/', $normalized)) {
            throw ValidationException::withMessages([
                'value' => 'Format waktu harus HH:MM.',
            ]);
        }

        [$hour, $minute] = explode(':', $normalized);

        return sprintf('%02d:%02d', (int) $hour, (int) $minute);
    }

    private function normalizeDate(mixed $value): string
    {
        $normalized = trim((string) $value);

        if (! preg_match('/^\d{4}-\d{2}-\d{2}$/', $normalized)) {
            throw ValidationException::withMessages([
                'value' => 'Format tanggal harus YYYY-MM-DD.',
            ]);
        }

        return $normalized;
    }
}
