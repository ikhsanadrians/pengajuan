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

        .title {
            font-size: 13px !important;
        }
    </style>
</head>

<body class="bg-white p-8">
    <div class="max-w-[21cm] mx-auto">
        <div class="flex justify-between items-center">
            <div class="flex gap-4 items-center">
                <div class="logo">
                    <img class="h-10" src="{{ asset('logo/ptitbreal.png') }}" alt="">
                </div>
                <p class="w-48 font-medium text-[13px]">
                    Jalan Cikunir Raya No.111, Jatiasih, Jawa Barat 17423
                </p>
            </div>
            <div class="p-3 border-2 border-black font-bold">
                Pengajuan Barang
            </div>
        </div>
        <div class="line h-[1.5px] w-full bg-black mt-4">
        </div>
        <div class="line h-[1.5px] w-full bg-black mt-1">
        </div>
        <div class="head mt-4 flex justify-between gap-4">
            <div class="left flex gap-3">
                <div class="kode">
                    <p class="title">
                        Kode Pengajuan
                    </p>
                    <h1 class="font-semibold">#{{ $detailPengajuanDatas->unique_id }}</h1>
                </div>
                <div class="tgl-pengajuan">
                    <p class="title">
                        Tanggal Pengajuan
                    </p>
                    <h1 class="font-semibold">{{ $detailPengajuanDatas->created_at }}</h1>
                </div>
                <div class="status">
                    <p class="title">
                        Status Pengajuan
                    </p>
                    <h1 class="font-semibold">{{ $detailPengajuanDatas->status_name }}</h1>
                </div>
            </div>
            <div class="right flex gap-3">
                <div class="diajukan-oleh">
                    <p class="title">
                        Diajukan Oleh
                    </p>
                    <h1 class="font-semibold">{{ $detailPengajuanDatas->user }}</h1>
                </div>
                <div class="tujuan-dept">
                    <p class="title">
                        Tujuan Departemen
                    </p>
                    <h1 class="font-semibold">{{ $detailPengajuanDatas->namadepartement }}</h1>
                </div>

            </div>
        </div>
        <div class="head-2 mt-4">
            <div class="tujuan-dept">
                <p class="title">
                    Keterangan
                </p>
                <h1 class="font-semibold">{{ $detailPengajuanDatas->keterangan }}</h1>
            </div>
        </div>
        <div class="table-barang mt-6 w-full">
            <p class="title">
                Daftar Barang Diajukan
            </p>
            <div class="table mt-2 overflow-x-auto w-full">
                <table class="w-full table-auto border-collapse text-[14px]">
                    <thead>
                        <tr>
                            <th class="border-[.5px] border-black/90 text-left px-4 py-2 font-medium">No</th>
                            <th class="border-[.5px] border-black/90 text-left px-4 py-2 font-medium">Nama Barang</th>
                            <th class="border-[.5px] border-black/90 text-left px-4 py-2 font-medium">Jumlah</th>
                            <th class="border-[.5px] border-black/90 text-left px-4 py-2 font-medium">Status</th>
                            <th class="border-[.5px] border-black/90 text-left px-4 py-2 font-medium">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detailPengajuanDatas->transaksi as $key => $transaksi)
                            <tr>
                                <td class="border-[.5px] border-black/90 px-4 py-2">{{ $key + 1 }}</td>
                                <td class="border-[.5px] border-black/90 px-4 py-2">{{ $transaksi->namabarang }}</td>
                                <td class="border-[.5px] border-black/90 px-4 py-2">{{ $transaksi->quantity }}</td>
                                <td class="border-[.5px] border-black/90 px-4 py-2">{{ $transaksi->status }}</td>
                                <td class="border-[.5px] border-black/90 px-4 py-2">{{ $transaksi->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mengetahui flex flex-col justify-end items-end mt-16 mr-8">
            <div class="wrapper">
                <p class="text-center">Mengetahui</p>
                <p class="mt-16 text-center">{{ $detailPengajuanDatas->user }}</p>
            </div>

        </div>
    </div>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>

</html>
