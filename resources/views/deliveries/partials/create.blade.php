{{-- @extends('layouts.auth')

@section('content')
<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Assign Paket ke Kurir</h4>

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('failed'))
        <div class="alert alert-danger">{{ session('failed') }}</div>
        @endif
        <div id="scan-alert-container"></div>

        <form action="{{ route('deliveries.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="courier_id">Pilih Kurir</label>
                <select name="courier_id" id="courier_id"
                  class="form-control @error('courier_id') is-invalid @enderror">
                  <option value="">-- Pilih Kurir --</option>
                  @foreach ($couriers as $courier)
                  <option value="{{ $courier->id }}" {{ old('courier_id')==$courier->id ? 'selected' :
                    '' }}>
                    {{ $courier->name }}
                  </option>
                  @endforeach
                </select>
                @error('courier_id')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group mt-4">
                <label>Scan Barcode</label>
                <button type="button" id="toggleScannerBtn" onclick="toggleScanner()"
                  class="btn btn-info d-flex align-items-center px-4 py-2">
                  <i class="mdi mdi-barcode-scan" style="font-size: 1.8rem; margin-right: 8px;"></i>
                  <span class="fw-bold">Scan Paket</span>
                </button>
              </div>


              <div class="form-group">
                <label for="paket_ids">Pilih Paket</label>
                <div class="form-check">
                  @foreach ($pakets as $paket)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="paket_ids[]" value="{{ $paket->id }}"
                      id="paket_{{ $paket->id }}" {{ in_array($paket->id,
                    old('paket_ids', [])) ? 'checked' : '' }}>
                    <label class="form-check-label" for="paket_{{ $paket->id }}">
                      {{ $paket->kode_paket }} - {{ optional($paket->detail)->nama_penerima ??
                      'Tanpa
                      Penerima' }}
                    </label>
                  </div>
                  @endforeach
                </div>
                @error('paket_ids')
                <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
              </div>

              <button type="submit" class="btn btn-primary">Assign</button>
              <a href="{{ route('deliveries.index') }}" class="btn btn-light">Cancel</a>
            </div>
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="min-height: 400px;">
              <div id="reader" style="width: 400px; max-width: 100%; border-radius: 8px;">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('script')
<script>
  let html5QrCode;
let isScanning = false;

window.toggleScanner = function () {
    const scannerId = "reader";
    const btn = document.getElementById("toggleScannerBtn");
    const btnIcon = btn.querySelector("i");
    const btnText = btn.querySelector("span");

    if (!html5QrCode) {
        html5QrCode = new Html5Qrcode(scannerId);
    }

    if (!isScanning) {
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            (decodedText, decodedResult) => {
                console.log("QR ditemukan:", decodedText);

                const checkbox = document.querySelector(`input[type="checkbox"][value="${decodedText}"]`);
                const alertContainer = document.getElementById("scan-alert-container");

                // Bersihkan alert sebelumnya
                alertContainer.innerHTML = '';

                if (checkbox) {
                    checkbox.checked = true;
                    const successAlert = document.createElement('div');
                    successAlert.className = 'alert alert-success';
                    successAlert.textContent = 'Paket berhasil discan: ' + decodedText;
                    alertContainer.appendChild(successAlert);

                    setTimeout(() => {
                        successAlert.remove();
                    }, 3000);
                    // alert("Paket berhasil discan: " + decodedText);
                } else {
                    alert("Paket tidak ditemukan untuk kode: " + decodedText);
                }
            },
            (err) => {
                console.warn("Scan error:", err);
            }
        ).then(() => {
            isScanning = true;
            btnIcon.className = "mdi mdi-close-circle-outline"; // Ubah ikon
            btnText.innerText = "Tutup Kamera";
        }).catch((err) => {
            console.error("Gagal memulai scanner:", err);
        });

    } else {
        html5QrCode.stop().then(() => {
            isScanning = false;
            btnIcon.className = "mdi mdi-barcode-scan"; // Kembalikan ikon awal
            btnText.innerText = "Buka Kamera";
        }).catch((err) => {
            console.error("Gagal menghentikan scanner:", err);
        });
    }
};

</script>
@endpush --}}



@extends('layouts.auth')

@section('content')
<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Assign Paket Ke Kurir</h4>

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('failed'))
        <div class="alert alert-danger">{{ session('failed') }}</div>
        @endif

        <div id="scan-alert-container"></div>

        <form action="{{ route('deliveries.store') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-6">

              {{-- PILIH KURIR --}}
              <div class="form-group">
                <label for="courier_id">Pilih Kurir</label>
                <select name="courier_id" id="courier_id"
                  class="form-control @error('courier_id') is-invalid @enderror">
                  <option value="">-- Pilih Kurir --</option>
                  @foreach ($couriers as $courier)
                  <option value="{{ $courier->id }}" {{ old('courier_id')==$courier->id ? 'selected' :
                    '' }}>
                    {{ $courier->name }}
                  </option>
                  @endforeach
                </select>
                @error('courier_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              {{-- SCANNER --}}
              <div class="form-group mt-4">
                <label>Scan Barcode</label>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                  <button type="button" id="toggleScannerBtn" onclick="toggleScanner()"
                    class="btn btn-info d-flex align-items-center px-4 py-2">
                    <i class="mdi mdi-barcode-scan" style="font-size:1.6rem;margin-right:8px;"></i>
                    <span class="fw-bold">Scan Paket</span>
                  </button>

                  {{-- (opsional) pilih kamera bila ada >1 --}}
                  <select id="cameraSelect" class="form-select" style="min-width:260px;display:none"></select>
                </div>
                <small class="text-muted d-block mt-2">
                  *Gunakan kamera belakang. Format didukung: Code-128, Code-39, EAN, UPC.
                </small>
              </div>

              {{-- DAFTAR PAKET --}}
              <div class="form-group">
                <label for="paket_ids">Pilih Paket</label>
                <div class="form-check">
                  @foreach ($pakets as $paket)
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="paket_ids[]" value="{{ $paket->id }}" {{--
                      nilai untuk submit --}} id="paket_{{ $paket->id }}" data-kode="{{ $paket->kode_paket }}" {{--
                      dipakai untuk cocokkan hasil scan --}} {{ in_array($paket->id,
                    old('paket_ids', [])) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="paket_{{ $paket->id }}">
                      {{ $paket->kode_paket }} — {{ optional($paket->detail)->nama_penerima ??
                      'Tanpa Penerima' }}
                    </label>
                  </div>
                  @endforeach
                </div>
                @error('paket_ids') <div class="text-danger mt-1">{{ $message }}</div> @enderror
              </div>

              <button type="submit" class="btn btn-primary">Assign</button>
              <a href="{{ route('deliveries.index') }}" class="btn btn-light">Cancel</a>
            </div>

            {{-- AREA KAMERA --}}
            <div class="col-md-6 d-flex justify-content-center align-items-center" style="min-height:400px;">
              <div id="reader" style="width:420px;max-width:100%;border-radius:8px;">
                <video id="barcode-video" style="width:100%;border-radius:8px;background:#000" muted
                  playsinline></video>
              </div>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</div>
@endsection

{{-- @push('script')
<script src="https://unpkg.com/@zxing/library@0.20.0/umd/index.min.js"></script>
<script>
  (() => {
  // ===== Konfigurasi =====
  const STOP_AFTER_SUCCESS = false;
  const MIN_MS_BETWEEN_SAME_CODE = 1200;
  const VIDEO_WIDTH  = { ideal: 1280 };
  const VIDEO_HEIGHT = { ideal: 720  };

  // ===== Elemen UI =====
  const els = {
    btn:   document.getElementById('toggleScannerBtn'),
    video: document.getElementById('barcode-video'),
    drop:  document.getElementById('cameraSelect'),
    alert: document.getElementById('scan-alert-container'),
  };

  // ===== State =====
  let reader = null;
  let activeControls = null;
  let scanning = false;
  let devices = [];
  const seen = new Map();

  // origin aman: https atau localhost
  const isLocal  = ['localhost','127.0.0.1','[::1]'].includes(location.hostname);
  const isSecure = location.protocol === 'https:' || isLocal;

  // ===== Utils UI =====
  function showAlert(type, html, ms=3000) {
    if (!els.alert) return;
    els.alert.innerHTML = `<div class="alert alert-${type} d-flex align-items-center justify-content-between">
      <div>${html}</div>
    </div>`;
    if (ms) setTimeout(()=>{ els.alert.innerHTML = '' }, ms);
  }
  function showAlertSticky(type, html){ showAlert(type, html, 0); }
  function setBtn(running) {
    const icon = els.btn?.querySelector('i');
    const span = els.btn?.querySelector('span');
    if (running) { if(icon)icon.className='mdi mdi-close-circle-outline'; if(span)span.innerText='Tutup Kamera'; }
    else { if(icon)icon.className='mdi mdi-barcode-scan'; if(span)span.innerText='Scan Paket'; }
  }
  function explainError(e) {
    // mapping pesan supaya jelas
    const name = e?.name || '';
    const msg  = e?.message || '';
    switch (name) {
      case 'NotAllowedError':
        return 'Izin kamera ditolak. Buka ikon 🔒/kamera di address bar → Allow.';
      case 'NotFoundError':
      case 'OverconstrainedError':
        return 'Kamera tidak ditemukan. Pastikan perangkat punya kamera & tidak sedang dipakai aplikasi lain.';
      case 'NotReadableError':
        return 'Kamera sedang dipakai aplikasi lain / tidak bisa diakses.';
      case 'SecurityError':
        return 'Origin tidak aman. Jalankan di HTTPS atau http://localhost.';
      default:
        return msg || 'Gagal mengakses kamera.';
    }
  }

  // ===== Normalisasi kode (kalau nanti sudah bisa scan) =====
  const normCode = (s) => String(s ?? '').trim().replace(/\s+/g,'').replace(/[^0-9A-Za-z]/g,'').toUpperCase();
  const normId   = (s) => {
    const str = String(s ?? '').trim();
    const m = str.match(/(?:^|[^0-9])(\d{1,})$/); // ambil angka di akhir
    return m ? m[1] : (str.match(/^\d+$/) ? str : null);
  };

  // index checkbox paket by id & kode
  function buildIndexes() {
    const mapById = new Map();
    const mapByKode = new Map();
    document.querySelectorAll('input[name="paket_ids[]"][data-id][data-kode]').forEach(cb=>{
      const id = String(cb.dataset.id);
      const kode = normCode(cb.dataset.kode);
      if (id) mapById.set(id, cb);
      if (kode) mapByKode.set(kode, cb);
      cb.dataset.kodenorm = kode;
    });
    return { mapById, mapByKode };
  }
  let INDEX = { mapById:new Map(), mapByKode:new Map() };

  function findCheckboxAuto(scanText) {
    const raw = String(scanText ?? '');
    const idCandidate = normId(raw);
    const kodeCandidate = normCode(raw);
    if (idCandidate && INDEX.mapById.has(idCandidate)) return INDEX.mapById.get(idCandidate);
    if (kodeCandidate && INDEX.mapByKode.has(kodeCandidate)) return INDEX.mapByKode.get(kodeCandidate);
    for (const [k, cb] of INDEX.mapByKode.entries()) {
      if (k.includes(kodeCandidate) || kodeCandidate.includes(k)) return cb;
    }
    if (idCandidate) {
      const cb = document.querySelector(`input[name="paket_ids[]"][value="${idCandidate}"]`);
      if (cb) return cb;
    }
    return null;
  }
  window.findCheckboxByScan = findCheckboxAuto; // buat uji manual

  function highlight(cb) {
    const label = document.querySelector(`label[for="${cb.id}"]`) || cb.parentElement;
    if (!label) return;
    const prev = label.style.backgroundColor;
    label.style.backgroundColor = 'rgba(25,135,84,.25)';
    setTimeout(()=> label.style.backgroundColor = prev || '', 500);
  }
  function beep() {
    try {
      const ctx = new (window.AudioContext || window.webkitAudioContext)();
      const o = ctx.createOscillator(), g = ctx.createGain();
      o.type='sine'; o.frequency.value=880; o.connect(g); g.connect(ctx.destination);
      g.gain.setValueAtTime(0.0001, ctx.currentTime);
      g.gain.exponentialRampToValueAtTime(0.2, ctx.currentTime + 0.01);
      o.start();
      setTimeout(()=>{ g.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.05); o.stop(); ctx.close(); },120);
    } catch {}
  }

  // ===== Permission flow (PERBAIKAN UTAMA) =====
  async function requestCameraPermission() {
    if (!isSecure) {
      throw new DOMException('Insecure origin', 'SecurityError');
    }
    // minta izin dulu, pakai facingMode environment
    const stream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: { ideal:'environment' } }, audio:false
    });
    // stop tracks; izin sudah didapat
    stream.getTracks().forEach(t => t.stop());
  }

  async function getVideoDevices() {
    const all = await navigator.mediaDevices.enumerateDevices();
    return all.filter(d => d.kind === 'videoinput');
  }

  async function ensureDevicesWithPermission() {
    try {
      await requestCameraPermission(); // minta izin diawal
    } catch (e) {
      const hint = explainError(e);
      const retryBtn = `<button type="button" id="retryCam" class="btn btn-sm btn-outline-danger ms-3">Coba Lagi</button>`;
      showAlertSticky('danger', hint + retryBtn);
      // attach sekali
      setTimeout(()=>{
        document.getElementById('retryCam')?.addEventListener('click', async ()=>{
          els.alert.innerHTML = '';
          await startScanner(); // coba ulang
        });
      },0);
      throw e; // hentikan flow
    }

    // setelah izin ada, enumerate
    const cams = await getVideoDevices();
    // isi dropdown bila >1
    devices = cams;
    if (els.drop) {
      if (devices.length <= 1) {
        els.drop.style.display = 'none';
      } else {
        els.drop.innerHTML = '';
        devices.forEach((d,i)=>{
          const opt = document.createElement('option');
          opt.value = d.deviceId;
          opt.textContent = d.label || `Camera ${i+1}`;
          els.drop.appendChild(opt);
        });
        const back = devices.find(d=>/back|rear|environment/i.test(d.label));
        els.drop.value = back ? back.deviceId : devices[0].deviceId;
        els.drop.style.display = 'block';
      }
    }
    return cams;
  }

  function chooseDeviceId() {
    if (els.drop && els.drop.style.display==='block' && els.drop.value) return els.drop.value;
    const back = devices.find(d=>/back|rear|environment/i.test(d.label));
    return back ? back.deviceId : (devices[0] && devices[0].deviceId);
  }

  // ===== ZXing Start/Stop =====
  async function startScanner() {
    try {
      // minta izin + dapatkan daftar kamera
      const cams = await ensureDevicesWithPermission();
      if (!cams.length) {
        showAlertSticky('danger', 'Kamera tidak ditemukan pada perangkat ini.');
        return;
      }

      if (!reader) {
        const hints = new Map();
        hints.set(ZXing.DecodeHintType.POSSIBLE_FORMATS, [
          ZXing.BarcodeFormat.CODE_128,
          ZXing.BarcodeFormat.CODE_39,
          ZXing.BarcodeFormat.EAN_13,
          ZXing.BarcodeFormat.EAN_8,
          ZXing.BarcodeFormat.UPC_A,
          ZXing.BarcodeFormat.UPC_E,
        ]);
        hints.set(ZXing.DecodeHintType.TRY_HARDER, true);
        reader = new ZXing.BrowserMultiFormatReader(hints);
      }

      const deviceId = chooseDeviceId();
      const constraints = {
        audio:false,
        video: deviceId
          ? { deviceId:{ exact:deviceId }, width:VIDEO_WIDTH, height:VIDEO_HEIGHT, focusMode:'continuous' }
          : { facingMode:{ ideal:'environment' }, width:VIDEO_WIDTH, height:VIDEO_HEIGHT, focusMode:'continuous' }
      };

      activeControls = await reader.decodeFromConstraints(
        constraints,
        els.video, // element <video> langsung
        (result, err, controls) => {
          if (result) {
            const raw = String((result.getText() || '').trim());
            const key = normCode(raw);
            const last = seen.get(key) || 0, now = Date.now();
            if (now - last < MIN_MS_BETWEEN_SAME_CODE) return;
            seen.set(key, now);

            const cb = findCheckboxAuto(raw);
            if (cb) {
              if (!cb.checked) cb.checked = true;
              cb.dispatchEvent(new Event('change', { bubbles:true }));
              highlight(cb);
              cb.scrollIntoView({ behavior:'smooth', block:'center' });
              beep();
              showAlert('success', 'Paket discan: ' + raw);
            } else {
              showAlert('warning', 'Paket tidak ditemukan: ' + raw);
            }

            if (STOP_AFTER_SUCCESS) {
              controls.stop(); scanning=false; setBtn(false);
            }
          } else if (err && !(err instanceof ZXing.NotFoundException)) {
            console.warn('[ZXing] error:', err);
          }
        }
      );

      scanning = true; setBtn(true);
      // bersihkan alert sticky kalau sukses
      els.alert.innerHTML = '';
    } catch (e) {
      console.error(e);
      // kalau belum tertangani di ensureDevices, tampilkan di sini
      const hint = explainError(e);
      const retryBtn = `<button type="button" id="retryCam" class="btn btn-sm btn-outline-danger ms-3">Coba Lagi</button>`;
      showAlertSticky('danger', hint + retryBtn);
      setTimeout(()=>{
        document.getElementById('retryCam')?.addEventListener('click', async ()=>{
          els.alert.innerHTML = '';
          await startScanner();
        });
      },0);
    }
  }

  async function stopScanner() {
    try {
      if (activeControls) { await activeControls.stop(); activeControls=null; }
      if (reader) reader.reset();
    } catch(e) { console.warn('Stop warn:', e); }
    finally { scanning=false; setBtn(false); }
  }

  // ===== Events & Init =====
  window.toggleScanner = () => scanning ? stopScanner() : startScanner();
  els.drop?.addEventListener('change', async () => { if (scanning) { await stopScanner(); await startScanner(); } });
  window.addEventListener('beforeunload', stopScanner);

  document.addEventListener('DOMContentLoaded', () => {
    // Bangun index untuk ceklis by id/kode
    // (Pastikan input checkbox punya data-id & data-kode)
    const inputs = document.querySelectorAll('input[name="paket_ids[]"]');
    inputs.forEach(cb => {
      if (!cb.dataset.id)   cb.dataset.id   = cb.value;        // fallback
      if (!cb.dataset.kode) cb.dataset.kode = cb.dataset.kode || '';
    });
    INDEX = buildIndexes();
  });
})();
</script>
@endpush --}}


@push('script')
<script src="https://unpkg.com/@zxing/library@0.20.0/umd/index.min.js"></script>
<script>
  (() => {
  // ==================== Konfigurasi ====================
  const STOP_AFTER_SUCCESS = false;
  const MIN_MS_BETWEEN_SAME_CODE = 1200;

  // Kamera
  const VIDEO_WIDTH  = { ideal: 1280 };
  const VIDEO_HEIGHT = { ideal: 720  };

  // Scanner eksternal (keyboard wedge)
  const EXT_ENABLED = true;                // aktifkan input eksternal
  const EXT_GLOBAL_CAPTURE = false;        // true = tangkap ketikan cepat global (hati2 ganggu input lain)
  const EXT_DEBOUNCE_MS = 120;             // jeda selesai mengetik
  const EXT_MIN_LEN = 3;                   // minimal panjang kode masuk akal
  const EXT_END_KEYS = new Set(['Enter','Tab']); // tombol akhir dari banyak alat
  const EXT_ALLOWED = /[0-9A-Za-z_\-]/;    // karakter yang disimpan di buffer global

  // ==================== Elemen UI ====================
  const els = {
    btn:   document.getElementById('toggleScannerBtn'),
    video: document.getElementById('barcode-video'),
    drop:  document.getElementById('cameraSelect'),
    alert: document.getElementById('scan-alert-container'),
  };

  // ==================== State ====================
  let reader = null;
  let activeControls = null;
  let scanning = false;
  let devices = [];
  const seen = new Map(); // anti-duplikat (key: kode-norm -> ts)
  let INDEX = { mapById:new Map(), mapByKode:new Map() };

  // origin aman: https atau localhost
  const isLocal  = ['localhost','127.0.0.1','[::1]'].includes(location.hostname);
  const isSecure = location.protocol === 'https:' || isLocal;

  // ==================== Utils UI ====================
  function showAlert(type, html, ms=3000) {
    if (!els.alert) return;
    els.alert.innerHTML = `<div class="alert alert-${type} d-flex align-items-center justify-content-between">
      <div>${html}</div>
    </div>`;
    if (ms) setTimeout(()=>{ els.alert.innerHTML = '' }, ms);
  }
  function showAlertSticky(type, html){ showAlert(type, html, 0); }
  function setBtn(running) {
    const icon = els.btn?.querySelector('i');
    const span = els.btn?.querySelector('span');
    if (running) { if(icon)icon.className='mdi mdi-close-circle-outline'; if(span)span.innerText='Tutup Kamera'; }
    else { if(icon)icon.className='mdi mdi-barcode-scan'; if(span)span.innerText='Scan Paket'; }
  }
  function explainError(e) {
    const name = e?.name || '';
    const msg  = e?.message || '';
    switch (name) {
      case 'NotAllowedError': return 'Izin kamera ditolak. Gunakan input “Scan alat (USB/Bluetooth)” di bawah atau izinkan kamera.';
      case 'NotFoundError':
      case 'OverconstrainedError': return 'Kamera tidak ditemukan. Gunakan input “Scan alat (USB/Bluetooth)” di bawah.';
      case 'NotReadableError': return 'Kamera sedang dipakai aplikasi lain.';
      case 'SecurityError': return 'Origin tidak aman. Pakai HTTPS atau http://localhost.';
      default: return msg || 'Gagal mengakses kamera.';
    }
  }

  // ==================== Normalisasi & Index ====================
  const normCode = (s) => String(s ?? '').trim().replace(/\s+/g,'').replace(/[^0-9A-Za-z]/g,'').toUpperCase();
  const normId   = (s) => { const str=String(s??'').trim(); const m=str.match(/(?:^|[^0-9])(\d{1,})$/); return m?m[1]:(/^\d+$/.test(str)?str:null); };

  function buildIndexes() {
    const mapById = new Map(), mapByKode = new Map();
    document.querySelectorAll('input[name="paket_ids[]"]').forEach(cb=>{
      if (!cb.dataset.id)   cb.dataset.id   = cb.value; // fallback
      if (!cb.dataset.kode) cb.dataset.kode = cb.dataset.kode || '';
      const id = String(cb.dataset.id);
      const kode = normCode(cb.dataset.kode);
      if (id) mapById.set(id, cb);
      if (kode) mapByKode.set(kode, cb);
      cb.dataset.kodenorm = kode;
    });
    INDEX = { mapById, mapByKode };
  }

  function findCheckboxAuto(scanText) {
    const raw = String(scanText ?? '');
    const idCandidate = normId(raw);
    const kodeCandidate = normCode(raw);
    if (idCandidate && INDEX.mapById.has(idCandidate)) return INDEX.mapById.get(idCandidate);
    if (kodeCandidate && INDEX.mapByKode.has(kodeCandidate)) return INDEX.mapByKode.get(kodeCandidate);
    for (const [k, cb] of INDEX.mapByKode.entries()) {
      if (k.includes(kodeCandidate) || kodeCandidate.includes(k)) return cb;
    }
    if (idCandidate) {
      const cb = document.querySelector(`input[name="paket_ids[]"][value="${idCandidate}"]`);
      if (cb) return cb;
    }
    return null;
  }
  window.findCheckboxByScan = findCheckboxAuto; // debug manual

  function highlight(cb) {
    const label = document.querySelector(`label[for="${cb.id}"]`) || cb.parentElement;
    if (!label) return;
    const prev = label.style.backgroundColor;
    label.style.backgroundColor = 'rgba(25,135,84,.25)';
    setTimeout(()=> label.style.backgroundColor = prev || '', 500);
  }
  function beep() {
    try {
      const ctx = new (window.AudioContext || window.webkitAudioContext)();
      const o = ctx.createOscillator(), g = ctx.createGain();
      o.type='sine'; o.frequency.value=880; o.connect(g); g.connect(ctx.destination);
      g.gain.setValueAtTime(0.0001, ctx.currentTime);
      g.gain.exponentialRampToValueAtTime(0.2, ctx.currentTime + 0.01);
      o.start();
      setTimeout(()=>{ g.gain.exponentialRampToValueAtTime(0.0001, ctx.currentTime + 0.05); o.stop(); ctx.close(); },120);
    } catch {}
  }

  function handleScanResult(raw) {
    const value = String(raw || '').trim();
    if (!value || value.length < EXT_MIN_LEN) return;

    const key = normCode(value);
    const last = seen.get(key) || 0, now = Date.now();
    if (now - last < MIN_MS_BETWEEN_SAME_CODE) return;
    seen.set(key, now);

    const cb = findCheckboxAuto(value);
    if (cb) {
      if (!cb.checked) cb.checked = true;
      cb.dispatchEvent(new Event('change', { bubbles:true }));
      highlight(cb);
      cb.scrollIntoView({ behavior:'smooth', block:'center' });
      beep();
      showAlert('success', 'Paket discan: ' + value);
    } else {
      showAlert('warning', 'Paket tidak ditemukan: ' + value);
    }
  }

  // ==================== Kamera (ZXing) ====================
  async function requestCameraPermission() {
    if (!isSecure) throw new DOMException('Insecure origin', 'SecurityError');
    const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal:'environment' } }, audio:false });
    stream.getTracks().forEach(t => t.stop());
  }
  async function getVideoDevices() {
    const all = await navigator.mediaDevices.enumerateDevices();
    return all.filter(d => d.kind === 'videoinput');
  }
  async function ensureDevicesWithPermission() {
    await requestCameraPermission();
    devices = await getVideoDevices();
    if (els.drop) {
      if (devices.length <= 1) els.drop.style.display = 'none';
      else {
        els.drop.innerHTML = '';
        devices.forEach((d,i)=>{
          const opt=document.createElement('option'); opt.value=d.deviceId; opt.textContent=d.label||`Camera ${i+1}`;
          els.drop.appendChild(opt);
        });
        const back=devices.find(d=>/back|rear|environment/i.test(d.label));
        els.drop.value = back ? back.deviceId : devices[0].deviceId;
        els.drop.style.display = 'block';
      }
    }
    return devices;
  }
  function chooseDeviceId() {
    if (els.drop && els.drop.style.display==='block' && els.drop.value) return els.drop.value;
    const back = devices.find(d=>/back|rear|environment/i.test(d.label));
    return back ? back.deviceId : (devices[0] && devices[0].deviceId);
  }

  async function startScanner() {
    try {
      const cams = await ensureDevicesWithPermission();
      if (!cams.length) {
        showAlertSticky('danger', 'Kamera tidak ditemukan. Gunakan input “Scan alat (USB/Bluetooth)” di bawah.');
        return;
      }
      if (!reader) {
        const hints = new Map();
        hints.set(ZXing.DecodeHintType.POSSIBLE_FORMATS, [
          ZXing.BarcodeFormat.CODE_128, ZXing.BarcodeFormat.CODE_39,
          ZXing.BarcodeFormat.EAN_13,  ZXing.BarcodeFormat.EAN_8,
          ZXing.BarcodeFormat.UPC_A,   ZXing.BarcodeFormat.UPC_E,
        ]);
        hints.set(ZXing.DecodeHintType.TRY_HARDER, true);
        reader = new ZXing.BrowserMultiFormatReader(hints);
      }
      const deviceId = chooseDeviceId();
      const constraints = {
        audio:false,
        video: deviceId
          ? { deviceId:{ exact:deviceId }, width:VIDEO_WIDTH, height:VIDEO_HEIGHT, focusMode:'continuous' }
          : { facingMode:{ ideal:'environment' }, width:VIDEO_WIDTH, height:VIDEO_HEIGHT, focusMode:'continuous' }
      };

      activeControls = await reader.decodeFromConstraints(
        constraints,
        els.video,
        (result, err, controls) => {
          if (result) {
            handleScanResult(String((result.getText() || '').trim()));
            if (STOP_AFTER_SUCCESS) { controls.stop(); scanning=false; setBtn(false); }
          } else if (err && !(err instanceof ZXing.NotFoundException)) {
            console.warn('[ZXing] error:', err);
          }
        }
      );
      scanning = true; setBtn(true); els.alert.innerHTML = '';
    } catch (e) {
      const hint = explainError(e);
      const retryBtn = `<button type="button" id="retryCam" class="btn btn-sm btn-outline-danger ms-3">Coba Lagi</button>`;
      showAlertSticky('danger', hint + retryBtn);
      setTimeout(()=>{ document.getElementById('retryCam')?.addEventListener('click', async ()=>{ els.alert.innerHTML=''; await startScanner(); }); },0);
    }
  }
  async function stopScanner() {
    try { if (activeControls) { await activeControls.stop(); activeControls=null; } if (reader) reader.reset(); }
    catch(e) { console.warn('Stop warn:', e); }
    finally { scanning=false; setBtn(false); }
  }

  // ==================== Scanner Eksternal ====================
  let extInput, extTimer=null, extBuf='';
  function ensureExternalInput() {
    if (!EXT_ENABLED) return;
    // buat input di bawah tombol scan
    const host = els.btn?.closest('.form-group') || document.body;
    const wrap = document.createElement('div');
    wrap.className = 'mt-2';
    wrap.innerHTML = `
      <label class="form-label mb-1" style="font-size:.9rem;">Scan alat (USB/Bluetooth)</label>
      <input id="externalScanInput" type="text" autocomplete="off" class="form-control"
             placeholder="Arahkan kursor ke sini lalu scan / tempel & tekan Enter" />
      <small class="text-muted">Menerima ID (mis. <code>123</code>/<code>paket_123</code>) atau <code>kode_paket</code>.</small>
    `;
    host.appendChild(wrap);
    extInput = document.getElementById('externalScanInput');

    // Enter/Tab = submit
    extInput.addEventListener('keydown', (e) => {
      if (EXT_END_KEYS.has(e.key)) {
        e.preventDefault();
        const v = extInput.value;
        extInput.value = '';
        handleScanResult(v);
      }
    });

    // Debounce submit (kalau alat tidak kirim Enter)
    extInput.addEventListener('input', () => {
      clearTimeout(extTimer);
      extTimer = setTimeout(() => {
        const v = extInput.value;
        if (v && v.length >= EXT_MIN_LEN) {
          extInput.value = '';
          handleScanResult(v);
        }
      }, EXT_DEBOUNCE_MS);
    });
  }

  // (Opsional) Global capture untuk wedge yang mengetik super cepat tanpa fokus input
  function enableGlobalCapture() {
    if (!EXT_GLOBAL_CAPTURE) return;
    let lastTs = 0;
    window.addEventListener('keydown', (e) => {
      // abaikan jika fokus di input/textarea/select (biar tidak ganggu ketikan user)
      const tag = (e.target && e.target.tagName) ? e.target.tagName.toLowerCase() : '';
      if (['input','textarea','select'].includes(tag)) return;

      const now = Date.now();
      if (now - lastTs > 400) extBuf = ''; // reset jika jeda terlalu lama
      lastTs = now;

      if (EXT_END_KEYS.has(e.key)) {
        e.preventDefault();
        const v = extBuf;
        extBuf = '';
        if (v && v.length >= EXT_MIN_LEN) handleScanResult(v);
        return;
      }

      // simpan karakter yang valid
      if (EXT_ALLOWED.test(e.key)) {
        extBuf += e.key;
        clearTimeout(extTimer);
        extTimer = setTimeout(() => {
          const v = extBuf; extBuf = '';
          if (v && v.length >= EXT_MIN_LEN) handleScanResult(v);
        }, EXT_DEBOUNCE_MS);
      }
    });
  }

  // ==================== Events & Init ====================
  window.toggleScanner = () => scanning ? stopScanner() : startScanner();
  els.drop?.addEventListener('change', async () => { if (scanning) { await stopScanner(); await startScanner(); } });
  window.addEventListener('beforeunload', stopScanner);

  document.addEventListener('DOMContentLoaded', () => {
    buildIndexes();
    ensureExternalInput();
    enableGlobalCapture();
  });
})();
</script>
@endpush