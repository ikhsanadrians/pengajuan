export const truncate = (text, length) => {
    return text.length > length ? text.substring(0, length) + '...' : text;
};


export const getStatusClass = (status) => {
    return {
        'bg-yellow-500 text-white': status === 'Request',
        'bg-orange-500 text-white': status === 'Ditinjau',
        'bg-green-500 text-white': status === 'Approved',
        'bg-red-500 text-white': status === 'Ditolak'
    };
};

export const checkIfVerifBtn = (status) => {
    return (status === 'Ditinjau' || status === 'Ditolak') ? { text: 'Sudah Verifikasi', icon: 'pi pi-check-circle', btnDisabled: 'true' } : { text: 'Simpan Verifikasi', icon: 'pi pi-book', btnDisabled: 'false' };
}

export const formatRupiah = (angka) => {
    if (!angka) return 'Rp 0';
    const numberString = angka.toString().replace(/[^,\d]/g, '');
    const split = numberString.split(',');
    const sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    const ribuan = split[0].substr(sisa).match(/\d{3}/gi);

    if (ribuan) {
        const separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
    return 'Rp ' + rupiah;
};
