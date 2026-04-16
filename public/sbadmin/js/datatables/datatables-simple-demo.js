window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const tableNodes = document.querySelectorAll('table#datatablesSimple, table[data-simple-datatable]');
    const initialized = new WeakSet();
    const isMobile = window.innerWidth < 768;

    tableNodes.forEach(table => {
        if (initialized.has(table)) {
            return;
        }

        new simpleDatatables.DataTable(table, {
            perPage: isMobile ? 5 : 10,
            perPageSelect: [5, 10, 20, 50],
            fixedHeight: false,
            searchable: table.dataset.searchable !== 'false',
            labels: {
                placeholder: 'Cari...',
                perPage: '{select} data per halaman',
                noRows: 'Tidak ada data',
                info: 'Menampilkan {start} - {end} dari {rows} data',
            }
        });

        initialized.add(table);
    });
});
