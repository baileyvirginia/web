<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pengurusan Laundry Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .sidebar-link.active { background-color: #2563eb; color: white; }
        .btn-primary { @apply bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition; }
    </style>
</head>
<body class="h-screen overflow-hidden flex">

    <!-- Login Screen -->
    <div id="login-screen" class="fixed inset-0 z-50 flex items-center justify-center bg-blue-600">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-blue-600">LAUNDRY PRO</h1>
                <p class="text-gray-500 italic">Solusi Digital Manajemen Laundry</p>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="admin / kasir / owner">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" class="w-full px-4 py-2 mt-1 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" value="password">
                </div>
                <button onclick="handleLogin()" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">Log Masuk</button>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div id="main-app" class="hidden flex w-full h-full">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white border-r flex flex-col">
            <div class="p-6 border-b">
                <h2 class="text-xl font-bold text-blue-600">LAUNDRY PRO</h2>
                <div class="flex items-center gap-2 mt-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                    <p id="user-role-display" class="text-xs uppercase tracking-widest text-gray-400 font-bold"></p>
                </div>
            </div>
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <button onclick="showPage('dashboard')" class="sidebar-link w-full text-left px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition flex items-center gap-3">📊 Dashboard</button>
                <button id="nav-member" onclick="showPage('member')" class="sidebar-link w-full text-left px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition flex items-center gap-3">👥 Registrasi Member</button>
                <button id="nav-transaksi" onclick="showPage('transaksi')" class="sidebar-link w-full text-left px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition flex items-center gap-3">💰 Transaksi</button>
                <button id="nav-laporan" onclick="showPage('laporan')" class="sidebar-link w-full text-left px-4 py-2 rounded-lg text-gray-600 hover:bg-gray-100 transition flex items-center gap-3">📝 Laporan</button>
            </nav>
            <div class="p-4 border-t">
                <button onclick="handleLogout()" class="w-full bg-red-50 text-red-600 py-2 rounded-lg font-medium hover:bg-red-100 transition">Logout</button>
            </div>
        </aside>

        <!-- Content Area -->
        <main class="flex-1 overflow-y-auto p-8" id="content-area"></main>
    </div>

    <!-- Modals -->
    <div id="modal-container" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-lg w-full p-6 shadow-xl" id="modal-content"></div>
    </div>

    <script>
        let currentUser = null;
        
        // Load data dari LocalStorage
        const db = {
            members: JSON.parse(localStorage.getItem('laundry_members')) || [],
            outlets: [{ id: 1, nama: 'Laundry Jaya', alamat: 'Jl. Melati', tlp: '0812' }],
            pakets: [
                { id: 1, nama_paket: 'Cuci Kering', harga: 5000 },
                { id: 2, nama_paket: 'Cuci Setrika', harga: 8000 }
            ],
            transaksi: JSON.parse(localStorage.getItem('laundry_transaksi')) || []
        };

        function syncDB() {
            localStorage.setItem('laundry_members', JSON.stringify(db.members));
            localStorage.setItem('laundry_transaksi', JSON.stringify(db.transaksi));
        }

        function handleLogin() {
            const username = document.getElementById('username').value.toLowerCase();
            if (['admin', 'kasir', 'owner'].includes(username)) {
                currentUser = { username, role: username, id_outlet: 1 };
                document.getElementById('login-screen').classList.add('hidden');
                document.getElementById('main-app').classList.remove('hidden');
                document.getElementById('user-role-display').innerText = currentUser.role;
                showPage('dashboard');
            } else {
                alert('User tidak valid!');
            }
        }

        function handleLogout() {
            currentUser = null;
            document.getElementById('login-screen').classList.remove('hidden');
            document.getElementById('main-app').classList.add('hidden');
        }

        function showPage(page) {
            const area = document.getElementById('content-area');
            document.querySelectorAll('.sidebar-link').forEach(btn => {
                btn.classList.toggle('active', btn.getAttribute('onclick').includes(`'${page}'`));
            });

            switch(page) {
                case 'dashboard': area.innerHTML = renderDashboard(); break;
                case 'member': area.innerHTML = renderMemberTable(); break;
                case 'transaksi': area.innerHTML = renderTransaksiForm(); break;
                case 'laporan': area.innerHTML = renderLaporan(); break;
            }
        }

        function renderDashboard() {
            return `
                <h1 class="text-2xl font-bold mb-6">Dashboard</h1>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-white">
                    <div class="bg-blue-600 p-6 rounded-xl shadow-lg">
                        <p class="opacity-80">Total Member</p>
                        <p class="text-4xl font-bold">${db.members.length}</p>
                    </div>
                    <div class="bg-green-600 p-6 rounded-xl shadow-lg">
                        <p class="opacity-80">Total Transaksi</p>
                        <p class="text-4xl font-bold">${db.transaksi.length}</p>
                    </div>
                </div>
            `;
        }

        function renderMemberTable() {
            return `
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Data Member</h1>
                    <button onclick="openMemberModal()" class="bg-blue-600 text-white px-4 py-2 rounded-lg">+ Tambah</button>
                </div>
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b text-gray-600">
                            <tr><th class="p-4">Nama</th><th class="p-4">Alamat</th><th class="p-4">Telepon</th><th class="p-4">Aksi</th></tr>
                        </thead>
                        <tbody>
                            ${db.members.map(m => `
                                <tr class="border-b">
                                    <td class="p-4">${m.nama}</td><td class="p-4">${m.alamat}</td><td class="p-4">${m.tlp}</td>
                                    <td class="p-4">
                                        <button onclick="confirmDelete(${m.id})" class="text-red-500 hover:underline">Hapus</button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function renderTransaksiForm() {
            return `
                <h1 class="text-2xl font-bold mb-6">Input Transaksi Baru</h1>
                <div class="bg-white p-6 rounded-xl border shadow-sm max-w-2xl">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-600">Pilih Member</label>
                            <select id="trx-member" class="w-full border p-2 rounded-lg mt-1">
                                ${db.members.map(m => `<option value="${m.id}">${m.nama}</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-600">Pilih Paket</label>
                            <select id="trx-paket" class="w-full border p-2 rounded-lg mt-1">
                                ${db.pakets.map(p => `<option value="${p.id}">${p.nama_paket} (Rp ${p.harga})</option>`).join('')}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-600">Berat (Kg)</label>
                            <input type="number" id="trx-qty" class="w-full border p-2 rounded-lg mt-1" value="1">
                        </div>
                        <button onclick="prosesSimpanTransaksi()" class="w-full bg-blue-600 text-white py-3 rounded-lg font-bold">Simpan Transaksi</button>
                    </div>
                </div>
            `;
        }

        function renderLaporan() {
            return `
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold">Laporan Transaksi</h1>
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm">Cetak Laporan</button>
                </div>
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 border-b">
                            <tr><th class="p-4">Invoice</th><th class="p-4">Pelanggan</th><th class="p-4">Total</th><th class="p-4">Status</th></tr>
                        </thead>
                        <tbody>
                            ${db.transaksi.length === 0 ? '<tr><td colspan="4" class="p-8 text-center text-gray-400">Belum ada transaksi</td></tr>' : 
                                db.transaksi.map(t => `
                                    <tr class="border-b">
                                        <td class="p-4 font-mono font-bold">${t.invoice}</td>
                                        <td class="p-4">${db.members.find(m => m.id == t.id_member)?.nama || 'Umum'}</td>
                                        <td class="p-4 font-bold text-blue-600">Rp ${t.total.toLocaleString()}</td>
                                        <td class="p-4"><span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">Baru</span></td>
                                    </tr>
                                `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function prosesSimpanTransaksi() {
            const idMember = document.getElementById('trx-member').value;
            const idPaket = document.getElementById('trx-paket').value;
            const qty = document.getElementById('trx-qty').value;
            const paket = db.pakets.find(p => p.id == idPaket);
            const total = paket.harga * qty;

            const newTrx = {
                id: Date.now(),
                invoice: 'INV-' + Date.now().toString().slice(-5),
                id_member: idMember,
                total: total,
                tgl: new Date().toLocaleDateString()
            };

            db.transaksi.push(newTrx);
            syncDB();
            alert('Transaksi Berhasil Disimpan!');
            showPage('laporan');
        }

        // CRUD Member sederhana
        function openMemberModal() {
            document.getElementById('modal-content').innerHTML = `
                <h2 class="text-xl font-bold mb-4">Tambah Member</h2>
                <input type="text" id="m-nama" placeholder="Nama" class="w-full border p-2 mb-2 rounded">
                <input type="text" id="m-alamat" placeholder="Alamat" class="w-full border p-2 mb-2 rounded">
                <input type="text" id="m-tlp" placeholder="Telepon" class="w-full border p-2 mb-4 rounded">
                <button onclick="saveMember()" class="w-full bg-blue-600 text-white p-2 rounded">Simpan</button>
            `;
            document.getElementById('modal-container').classList.remove('hidden');
        }

        function saveMember() {
            const m = { id: Date.now(), nama: document.getElementById('m-nama').value, alamat: document.getElementById('m-alamat').value, tlp: document.getElementById('m-tlp').value };
            db.members.push(m);
            syncDB();
            document.getElementById('modal-container').classList.add('hidden');
            showPage('member');
        }

        function confirmDelete(id) {
            if(confirm("Hapus member ini?")) {
                db.members = db.members.filter(m => m.id !== id);
                syncDB();
                showPage('member');
            }
        }

        window.onload = () => { if (!currentUser) document.getElementById('login-screen').classList.remove('hidden'); };
    </script>
</body>
</html>