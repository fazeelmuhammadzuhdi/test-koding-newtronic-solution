<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Nomor Antrian</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            text-align: center;
            margin-top: 80px;
        }

        .header {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .ticket {
            background-color: #ffffff;
            color: #000;
            font-size: 48px;
            border-radius: 10px;
            display: inline-block;
        }

        .divider {
            border-top: 2px dashed #000;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <p>Selamat Datang Di Layanan {{ strtoupper($noAntrianTeller->layanan) }}</p>
            <p>Silakan Tunggu Giliran Anda Di Panggil</p>
        </div>
        <div class="divider"></div>

        <div class="ticket">
            <p>Halo, {{ $noAntrianTeller->nasabah->nama }}</p>
            <p>Nomor Urut Antrian Anda Adalah :</p>
            <p>{{ $noAntrianTeller->no_antrian }}</p>
        </div>
        <div class="divider"></div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>
