<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Paket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Barryvdh\DomPDF\Facade\Pdf;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $pakets = Paket::with('detail')->latest()->paginate(10);
        return view('pakets.index', compact('pakets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('pakets.partials.create');
    }

    /**
     * Store a newly created resource in storage.
     *  @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // 'name' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'tanggal_pengiriman' => 'required|date',
            'nama_penerima' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'jenis_barang' => 'required|string',
            'berat' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $kode = 'PKT' . now()->format('Ymd') . strtoupper(Str::random(5));

            $paket = Paket::create([
                'tanggal_pengiriman' => $validated['tanggal_pengiriman'],
                'kode_paket' => $kode,
                'status' => 'Pending'
            ]);

            Location::create([
                'name' => $validated['nama_penerima'],
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
                'paket_id' => $paket->id,
            ]);

            $paket->detail()->create([
                'nama_penerima' => $validated['nama_penerima'],
                'alamat_lengkap' => $validated['alamat_lengkap'],
                'jenis_barang' => $validated['jenis_barang'],
                'berat' => $validated['berat'],
            ]);

            DB::commit();
            // return response()->json(['message' => 'Paket berhasil disimpan', 'kode_paket' => $kode], 201);
            return redirect()->route('pakets.index')->with(['success' => 'Paket berhasil disimpan', 'kode_paket' => $kode]);
        } catch (\Exception $e) {
            DB::rollback();
            // return response()->json(['error' => $e->getMessage()], 500);
            return redirect()->route('pakets.create')->with(['success' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $paket = Paket::with('detail', 'location')
            ->where('id', $id)
            // ->orWhere('kode_paket', $identifier)
            ->firstOrFail();
        // dd($paket->toArray());

        return view('pakets.partials.show', compact('paket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $paket = Paket::with('detail', 'location')
            ->where('id', $id)
            // ->orWhere('kode_paket', $identifier)
            ->firstOrFail();
        //render view with product
        return view('pakets.partials.edit', compact('paket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $validated = $request->validate([
            // 'name' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'tanggal_pengiriman' => 'required|date',
            'nama_penerima' => 'required|string',
            'alamat_lengkap' => 'required|string',
            'jenis_barang' => 'required|string',
            'berat' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            $paket = Paket::with(['detail', 'location'])->findOrFail($id);

            $paket->update([
                'tanggal_pengiriman' => $validated['tanggal_pengiriman'],
            ]);

            $paket->location->update([
                'name' => $validated['nama_penerima'],
                'lat' => $validated['lat'],
                'lng' => $validated['lng'],
            ]);

            $paket->detail()->delete();

            $paket->detail()->create([
                'nama_penerima' => $validated['nama_penerima'],
                'alamat_lengkap' => $validated['alamat_lengkap'],
                'jenis_barang' => $validated['jenis_barang'],
                'berat' => $validated['berat'],
            ]);

            DB::commit();
            return redirect()->route('pakets.index')->with('success', 'Paket updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('pakets.edit', $id)->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $paket = Paket::with('location')->findOrFail($id);
            $paket->delete();

            DB::commit();
            return redirect()->route('pakets.index')->with('success', 'Delete paket successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // public function downloadPendingBarcodesPdf()
    // {
    //     $pakets = Paket::select('id', 'kode_paket', 'tanggal_pengiriman', 'status')
    //         ->whereRaw('LOWER(status) = ?', ['pending'])
    //         ->orderBy('tanggal_pengiriman', 'asc')
    //         ->get();

    //     if ($pakets->isEmpty()) {
    //         return redirect()->route('pakets.index')
    //             ->with('error', 'Tidak ada paket berstatus Pending.');
    //     }

    //     $generator = new BarcodeGeneratorPNG();

    //     // Siapkan data untuk view PDF: kode + barcode base64
    //     $items = $pakets->map(function ($p) use ($generator) {
    //         $kode = $p->kode_paket ?: ('PKT-' . $p->id);
    //         // scale 3, height 80px (ubah sesuai kebutuhan)
    //         $png = $generator->getBarcode($kode, $generator::TYPE_CODE_128, 3, 80);
    //         $base64 = 'data:image/png;base64,' . base64_encode($png);

    //         return [
    //             'kode' => $kode,
    //             'tanggal' => optional($p->tanggal_pengiriman)->format('Y-m-d') ?? '-',
    //             'img' => $base64,
    //         ];
    //     });

    //     $pdf = Pdf::loadView('pakets.pdf.barcodes', [
    //         'items' => $items,
    //         'title' => 'Pending Barcodes',
    //     ])->setPaper('a4', 'portrait');

    //     $filename = 'pending-barcodes-' . now()->format('Ymd_His') . '.pdf';
    //     return $pdf->download($filename);
    // }

    // public function downloadPendingBarcodesPdf()
    // {
    //     $pakets = Paket::select('id', 'kode_paket', 'tanggal_pengiriman', 'status')
    //         ->whereRaw('LOWER(status)=?', ['pending'])
    //         ->orderBy('tanggal_pengiriman', 'asc')
    //         ->get();

    //     if ($pakets->isEmpty()) {
    //         return redirect()->route('pakets.index')->with('error', 'Tidak ada paket Pending.');
    //     }

    //     $gen = new BarcodeGeneratorPNG();

    //     $items = $pakets->map(function ($p) use ($gen) {
    //         $kode = $p->kode_paket ?: ('PKT-' . $p->id);
    //         $png  = $gen->getBarcode($kode, $gen::TYPE_CODE_128, 6, 150); // lebar & tinggi “scanner-friendly”
    //         return [
    //             'kode'    => $kode,
    //             'tanggal' => optional($p->tanggal_pengiriman)->format('Y-m-d') ?? '-',
    //             'img'     => 'data:image/png;base64,' . base64_encode($png),
    //         ];
    //     });

    //     $pdf = Pdf::loadView('pakets.pdf.barcodes', [
    //         'title' => 'Pending Barcodes',
    //         'items' => $items,
    //     ])->setPaper('a4', 'portrait');

    //     $pdf->set_option('dpi', 150); // jaga garis tidak pecah
    //     return $pdf->download('pending-barcodes-' . now()->format('Ymd_His') . '.pdf');
    // }

    public function downloadPendingBarcodesPdf()
    {
        $pakets = Paket::select('id', 'kode_paket', 'tanggal_pengiriman', 'status')
            ->whereRaw('LOWER(status) = ?', ['pending'])
            ->orderBy('tanggal_pengiriman', 'asc')
            ->get();

        if ($pakets->isEmpty()) {
            return redirect()->route('pakets.index')->with('error', 'Tidak ada paket Pending.');
        }

        $gen = new BarcodeGeneratorPNG();

        // === BARCODE BY ID (BUKAN kode_paket) ===
        $items = $pakets->map(function ($p) use ($gen) {
            $text = (string) $p->id; // <-- inilah yang di-encode ke barcode
            // sesuaikan scale/height sesuai kebutuhan cetak
            $png  = $gen->getBarcode($text, $gen::TYPE_CODE_128, 6, 150);

            return [
                'id'      => $p->id,
                'kode'    => $p->kode_paket, // untuk ditampilkan sebagai info (opsional)
                'tanggal' => optional($p->tanggal_pengiriman)->format('Y-m-d') ?? '-',
                'img'     => 'data:image/png;base64,' . base64_encode($png),
                'text'    => $text, // human-readable yang ditulis di bawah barcode
            ];
        });

        $pdf = Pdf::loadView('pakets.pdf.barcodes', [
            'title' => 'Pending Barcodes (by ID)',
            'items' => $items,
        ])->setPaper('a4', 'portrait');

        $pdf->set_option('dpi', 150);
        return $pdf->download('pending-barcodes-id-' . now()->format('Ymd_His') . '.pdf');
    }
}
