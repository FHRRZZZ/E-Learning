@extends('layouts.admin')

@section('title', 'Scan Absensi')

@section('content')
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h1, h2 {
        /* color: #1e40af; */
    }

    .btn {
        background-color: #2563eb;
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }
    .btn:hover {
        background-color: #1e40af;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    }

    .input-file {
        border: 1px solid #d1d5db;
        padding: 10px;
        border-radius: 8px;
        width: 100%;
        max-width: 350px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.3s, box-shadow 0.3s;
    }
    .input-file:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.3);
    }

    .container {
        display: flex;
        flex-wrap: wrap;
        gap: 32px;
        margin-top: 20px;
    }

    .left, .right {
        flex: 1;
        min-width: 300px;
    }

    #video {
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        max-width: 400px;
        width: 100%;
        margin-bottom: 16px;
        transform: scaleX(-1);
    }

    #scannedQRContainer img {
        max-width: 220px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .scanned-user {
        padding: 16px;
        border-radius: 12px;
        background-color: #ecfdf5;
        border: 1px solid #16a34a;
        margin-top: 16px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-radius: 12px;
        overflow: hidden;
    }

    th, td {
        padding: 12px 16px;
        text-align: left;
        font-size: 14px;
    }

    th {
        background-color: #f3f4f6;
        color: #2563eb;
        font-weight: 600;
    }

    tbody tr:nth-child(even) {
        background-color: #f9fafb;
    }

    tbody tr:hover {
        background-color: #e0e7ff;
    }

    .status-hadir {
        color: #16a34a;
        font-weight: 600;
        background-color: #dcfce7;
        padding: 4px 10px;
        border-radius: 12px;
        display: inline-block;
    }

    .status-tidak-hadir {
        color: #b91c1c;
        font-weight: 600;
        background-color: #fee2e2;
        padding: 4px 10px;
        border-radius: 12px;
        display: inline-block;
    }

    .head {
        color: #1e40af
    }
</style>

<h1 class="head">Scan QR Code Absensi</h1>

<div class="container">
    <div class="left">
        <video id="video" width="400" height="300" autoplay playsinline></video>
        <div id="result" style="margin-bottom: 16px;"></div>
        <button id="startButton" class="btn">Mulai Scan</button>

        <div style="margin-top: 16px;">
            <label for="uploadQRCode" class="font-semibold block mb-2">Atau Upload Gambar QR Code:</label>
            <input type="file" id="uploadQRCode" accept="image/*" class="input-file" />
        </div>

        <div id="scannedQRContainer" style="display:none; margin-top: 16px;">
            <h2 class="font-semibold mb-2">QR Code Terbaca:</h2>
            <img id="scannedQRImage" alt="QR Code scanned" />
        </div>

        <div id="scannedUserContainer" class="scanned-user" style="display:none;">
            <h2 class="font-semibold mb-2">Data User Hasil Scan</h2>
            <p><strong>ID:</strong> <span id="scannedUserId"></span></p>
            <p><strong>Nama:</strong> <span id="scannedUserName"></span></p>
            <p><strong>NISN:</strong> <span id="scannedUserNisn"></span></p>
            <p><strong>Status:</strong> <span id="scannedUserStatus"></span></p>
        </div>
    </div>

    <div class="right">
        <h2 class="font-semibold text-lg mb-4">Daftar User Hari Ini</h2>
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NISN</th>
                    <th>Status Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    @php
                        $hadirHariIni = $user->presensis->isNotEmpty() && $user->presensis->first()->aksi === 'hadir';
                    @endphp
                    <tr data-user-id="{{ $user->id }}">
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->nisn }}</td>
                        <td class="status-kehadiran">
                            @if($hadirHariIni)
                                <span class="status-hadir">Hadir</span>
                            @else
                                <span class="status-tidak-hadir">Tidak Hadir</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script>
    const video = document.getElementById('video');
    const result = document.getElementById('result');
    const startButton = document.getElementById('startButton');
    const uploadInput = document.getElementById('uploadQRCode');
    const scannedQRImage = document.getElementById('scannedQRImage');
    const scannedQRContainer = document.getElementById('scannedQRContainer');
    const scannedUserContainer = document.getElementById('scannedUserContainer');

    let scanning = false;
    let videoStream;

    startButton.addEventListener('click', () => {
        scanning ? stopScan() : startScan();
    });

    uploadInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        readQRCodeFromFile(file);
    });

    function readQRCodeFromFile(file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.width = img.width;
                canvas.height = img.height;
                context.drawImage(img, 0, 0);
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const code = jsQR(imageData.data, imageData.width, imageData.height);
                if (code) {
                    showQRCodeImage(canvas);
                    sendToServer(code.data);
                } else {
                    result.style.color = 'red';
                    result.textContent = "Gagal membaca QR Code.";
                    scannedQRContainer.style.display = 'none';
                }
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    }

    function startScan() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(stream => {
                scanning = true;
                startButton.textContent = "Berhenti Scan";
                videoStream = stream;
                video.srcObject = stream;
                video.setAttribute('playsinline', true);
                video.play();
                tick();
            })
            .catch(err => {
                result.style.color = 'red';
                result.textContent = 'Gagal akses kamera: ' + err;
            });
    }

    function stopScan() {
        scanning = false;
        startButton.textContent = "Mulai Scan";
        video.pause();
        if (videoStream) videoStream.getTracks().forEach(t => t.stop());
    }

    function tick() {
        if (!scanning) return;

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        // Atur resolusi canvas kecil supaya lebih cepat
        const width = 300;
        const height = 200;

        canvas.width = width;
        canvas.height = height;

        context.drawImage(video, 0, 0, width, height);

        const imageData = context.getImageData(0, 0, width, height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
            stopScan();
            showQRCodeImage(canvas);
            sendToServer(code.data);
        } else {
            requestAnimationFrame(tick);
        }
    }

    function showQRCodeImage(canvas) {
        scannedQRImage.src = canvas.toDataURL('image/png');
        scannedQRContainer.style.display = 'block';
    }

    function sendToServer(userId) {
        result.style.color = 'black';
        result.textContent = 'Memproses...';
        fetch("{{ route('admin.absensi.scan.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ user_id: userId })
        })
        .then(async res => {
            const json = await res.json().catch(()=>null);
            if (!res.ok) throw { status: res.status, body: json };
            return json;
        })
        .then(data => {
            result.style.color = 'green';
            result.textContent = data.message;

            scannedUserContainer.style.display = 'block';
            document.getElementById('scannedUserId').textContent = data.user.id;
            document.getElementById('scannedUserName').textContent = data.user.name;
            document.getElementById('scannedUserNisn').textContent = data.user.nisn;
            document.getElementById('scannedUserStatus').textContent = data.user.status;

            const statusCell = document.querySelector(`tr[data-user-id="${data.user.id}"] .status-kehadiran`);
            if (statusCell) {
                statusCell.innerHTML = `<span class="status-hadir">Hadir</span>`;
            }
        })
        .catch(err => {
            let msg = 'Terjadi kesalahan';
            if (err && err.body && err.body.message) msg = err.body.message;
            result.style.color = 'red';
            result.textContent = msg;
        });
    }
</script>
@endsection
