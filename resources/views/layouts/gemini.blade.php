<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gemini Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            height: 100vh;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            background: #f1f5f9;
        }

        header {
            background: #1e3a8a;
            color: white;
            padding: 15px 20px;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        header a {
            text-decoration: none;
            background: #2563eb;
            color: white;
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 14px;
            transition: background 0.2s;
        }

        header a:hover {
            background: #1e40af;
        }

        #out {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            margin: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            box-shadow: inset 0 0 6px rgba(0,0,0,0.05);
            scroll-behavior: smooth;
        }

        .bubble {
            max-width: 75%;
            padding: 12px 15px;
            border-radius: 12px;
            font-size: 15px;
            line-height: 1.4;
            white-space: pre-wrap;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .user {
            align-self: flex-end;
            background: #2563eb;
            color: white;
        }

        .bot {
            align-self: flex-start;
            background: #f3f4f6;
            color: #111827;
        }

        .input-area {
            display: flex;
            padding: 12px;
            border-top: 1px solid #ddd;
            background: white;
        }

        .input-area input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
            font-size: 15px;
        }

        .input-area button {
            margin-left: 10px;
            padding: 0 20px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s;
        }

        .input-area button:hover {
            background-color: #1e40af;
        }
    </style>
</head>
<body>
    <header>
        <span>💬 Gemini Chat</span>
        <a href="{{ route('user.dashboard') }}">⬅ Kembali</a>
    </header>

    <div id="out"></div>

    <div class="input-area">
        <input type="text" id="pesan" placeholder="Masukkan pesan...">
        <button onclick="pesan()">Kirim</button>
    </div>

    <script>

        const userName = "{{ auth()->user()->name }}";

        document.getElementById("pesan").addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();
                pesan();
            }
        })

        function pesan() {
            const pesan = document.getElementById("pesan").value;
            if (!pesan.trim()) return;

            const p = document.createElement("p");
            p.className = "bubble user"
            p.textContent = pesan;
            document.getElementById("out").appendChild(p);

            const p2 = document.createElement("p");
            p2.className = "bubble bot"
            p2.textContent = "Sedang mengetik..."
            document.getElementById("out").appendChild(p2);

            document.getElementById("out").scrollTop = document.getElementById("out").scrollHeight;

            geminiChat(pesan).then(balas => {
                p2.textContent = balas;
                document.getElementById("out").scrollTop = document.getElementById("out").scrollHeight;
            })

            document.getElementById("pesan").value =  "";
        }

        function geminiChat(prompt) {
            const Apikey = "AIzaSyAq7J1HrYx6PW_i7gDGKdrTWpO7KqBHeuI";
            return fetch(`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=${Apikey}`,
                {
                    method: "POST",
                    headers: {"Content-Type": "application/json"},
                    body: JSON.stringify({
                        contents:[{
                            role: "user",
                            parts: [
                                {text: `Kamu adalah asisten AI yang dikhsuskan untuk SDN 15 Gantung dan hanya menjawab materi tentang sekolah dasar tidak boleh yang lain.
                                Jika user menyapa (misalnya: hai, halo, pagi), balas dengan sapaan balik sambil sebut nama user: ${userName}.
                                Selain itu, jawab seperti biasa.`},
                                {text: prompt}
                            ]
                        }]
                    })
                }
            )
            .then(res => res.json())
            .then(data => {
                if(data.candidates && data.candidates.length > 0) {
                    return data.candidates[0].content.parts[0].text
                } else {
                    console.error("api error :", data)
                    return "⚠️ Gagal mendapatkan jawaban"
                }
            }).catch(err => {
                console.error(err);
                return "⚠️ Terjadi kesalahan"
            });
        }
    </script>
</body>
</html>
