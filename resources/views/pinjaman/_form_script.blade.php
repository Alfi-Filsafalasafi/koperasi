    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bungaInput = document.getElementById('bunga');
            const jumlahPinjamanInput = document.getElementById('jumlah_pinjaman');
            const jangkaWaktuSelect = document.getElementById('jangka_waktu');
            const angsuranPokokInput = document.getElementById('angsuran_pokok');
            const nisbahInput = document.getElementById('nisbah');

            // Set default bunga = 1.5%
            bungaInput.value = 1.5;

            function hitungAngsuranDanNisbah() {
                const jumlah = parseFloat(jumlahPinjamanInput.value) || 0;
                const waktu = parseInt(jangkaWaktuSelect.value) || 0;
                const bunga = 1.5;

                // Hitung angsuran pokok
                if (jumlah > 0 && waktu > 0) {
                    angsuranPokokInput.value = (jumlah / waktu).toFixed(2);
                } else {
                    angsuranPokokInput.value = '';
                }

                // Hitung nisbah: bunga * jumlah pinjaman per bulan
                nisbahInput.value = ((jumlah * bunga) / 100).toFixed(2);
            }

            jumlahPinjamanInput.addEventListener('input', hitungAngsuranDanNisbah);
            jangkaWaktuSelect.addEventListener('change', hitungAngsuranDanNisbah);

            const totalPinjamanInput = document.getElementById('total_pinjaman');

            function hitungAngsuranDanNisbah() {
                const jumlah = parseFloat(jumlahPinjamanInput.value) || 0;
                const waktu = parseInt(jangkaWaktuSelect.value) || 0;
                const bunga = 1.5;

                // Hitung angsuran pokok
                if (jumlah > 0 && waktu > 0) {
                    angsuranPokokInput.value = (jumlah / waktu).toFixed(2);
                } else {
                    angsuranPokokInput.value = '';
                }

                // Hitung nisbah (per bulan)
                const nisbah = (jumlah * bunga) / 100;
                nisbahInput.value = nisbah.toFixed(2);

                // Hitung total pinjaman
                const totalPinjaman = jumlah + (nisbah * waktu);
                totalPinjamanInput.value = totalPinjaman.toFixed(2);
            }

            const tanggalPinjamanInput = document.getElementById('tanggal_pinjaman');
            const tanggalJatuhTempoInput = document.getElementById('tanggal_jatuh_tempo');

            function hitungTanggalJatuhTempo() {
                const tanggalPinjaman = new Date(tanggalPinjamanInput.value);
                const jangkaWaktu = parseInt(jangkaWaktuSelect.value) || 0;

                if (tanggalPinjaman.toString() !== 'Invalid Date' && jangkaWaktu > 0) {
                    tanggalPinjaman.setMonth(tanggalPinjaman.getMonth() + jangkaWaktu);
                    const tahun = tanggalPinjaman.getFullYear();
                    const bulan = (tanggalPinjaman.getMonth() + 1).toString().padStart(2, '0');
                    const hari = tanggalPinjaman.getDate().toString().padStart(2, '0');

                    tanggalJatuhTempoInput.value = `${tahun}-${bulan}-${hari}`;
                } else {
                    tanggalJatuhTempoInput.value = '';
                }
            }

            tanggalPinjamanInput.addEventListener('change', hitungTanggalJatuhTempo);
            jangkaWaktuSelect.addEventListener('change', hitungTanggalJatuhTempo);

        });
    </script>
