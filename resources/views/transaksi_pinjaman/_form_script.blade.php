<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPinjaman = document.getElementById('pinjaman_id');
        const inputCicilan = document.getElementById('cicilan_ke');
        const inputSisaPinjaman = document.getElementById('sisa_pinjaman');
        const inputAngsuranPokok = document.getElementById('angsuran_pokok');
        const inputNisbah = document.getElementById('nisbah');
        const pembayaranPokok = document.getElementById('pembayaran_pokok');
        const pembayaranNisbah = document.getElementById('pembayaran_nisbah');
        const pembayaranDenda = document.getElementById('pembayaran_denda');
        const totalPembayaran = document.getElementById('total_pembayaran');

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        // Hitung cicilan ke-
        selectPinjaman.addEventListener('change', function() {

            const pinjamanId = this.value;
            if (!pinjamanId) return;

            fetch(`/pinjaman/${pinjamanId}/cicilan-terakhir`)
                .then(response => response.json())
                .then(data => {
                    inputCicilan.value = data.cicilan_ke;
                    inputSisaPinjaman.innerHTML = "Sisa Pinjaman = " + formatRupiah(data
                        .sisa_pinjaman);
                    inputAngsuranPokok.innerHTML = "Angsuran Pokok = " + formatRupiah(data
                        .angsuran_pokok);
                    inputNisbah.innerHTML = "Nisbah = " + formatRupiah(data.nisbah);
                    pembayaranPokok.value = data.angsuran_pokok;
                    pembayaranNisbah.value = data.nisbah;

                    hitungTotalPembayaran();

                })
                .catch(error => {
                    console.error('Gagal ambil cicilan:', error);
                    inputCicilan.value = '';
                });
        });



        function hitungTotalPembayaran() {
            const pokok = parseFloat(pembayaranPokok.value) || 0;
            const nisbah = parseFloat(pembayaranNisbah.value) || 0;
            const denda = parseFloat(pembayaranDenda.value) || 0;
            totalPembayaran.value = (pokok + nisbah + denda).toFixed(2);
        }

        pembayaranPokok.addEventListener('input', hitungTotalPembayaran);
        pembayaranNisbah.addEventListener('input', hitungTotalPembayaran);
        pembayaranDenda.addEventListener('input', hitungTotalPembayaran);
    });
</script>
