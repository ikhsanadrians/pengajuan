<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pengajuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            * {
                print-color-adjust: exact;
            }

            body {
                background-color: white !important;
                padding: 2rem !important;

            }

            .line {
                background-color: black;
            }
        }
    </style>
</head>

<body class="bg-white p-8">
    <div class="max-w-[21cm] mx-auto">
        <div class="flex justify-between items-center">
            <div class="logo">
                <img class="h-10" src="{{ asset('logo/ptitbreal.png') }}" alt="">
            </div>
            <div class="font-semibold">
                PT Intertama Trikenca Bersinar
            </div>
        </div>
        <div class="line h-[.5px] w-full bg-black mt-4">
        </div>
        <div class="head">
            <div class="kode">
                Kode Barang
            </div>
        </div>
    </div>
</body>

</html>
