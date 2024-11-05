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

