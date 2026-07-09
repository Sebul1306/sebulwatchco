<?= $this->extend("layout") ?>



<?= $this->section("content") ?>

<style>
  /* ── Google Font ── */
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

  .produk-wrap { font-family: 'Plus Jakarta Sans', sans-serif; }

  /* ── Stats Row ── */
  .stat-card {
    border: none;
    border-radius: 20px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 20px;
    color: #fff;
    box-shadow: 0 10px 20px rgba(0,0,0,.08);
    transition: transform .3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow .3s;
    position: relative;
    overflow: hidden;
  }
  .stat-card::after {
    content: ''; position: absolute; top: -50%; right: -10%;
    width: 150px; height: 150px; background: rgba(255,255,255,0.15);
    border-radius: 50%;
  }
  .stat-card.blue  { background: linear-gradient(135deg, #2876f9 0%, #6d17cb 100%); box-shadow: 0 8px 24px rgba(40,118,249,.3); }
  .stat-card.green { background: linear-gradient(135deg, #10b981 0%, #059669 100%); box-shadow: 0 8px 24px rgba(16,185,129,.3); }
  .stat-card.amber { background: linear-gradient(135deg, #ff0844 0%, #ffb199 100%); box-shadow: 0 8px 24px rgba(255,8,68,.3); }
  .stat-card:hover { transform: translateY(-6px) scale(1.02); box-shadow: 0 16px 32px rgba(0,0,0,.15); }
  
  .stat-icon {
    width: 60px; height: 60px; border-radius: 16px;
    background: rgba(255, 255, 255, 0.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; flex-shrink: 0; backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.3);
  }
  .stat-label { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; opacity: 0.9; margin-bottom: 4px; }
  .stat-value { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 38px; font-weight: 800; line-height: 1; letter-spacing: -1px; text-shadow: 0 2px 8px rgba(0,0,0,0.2); }

  .produk-toolbar {
    display: flex; align-items: center; justify-content: space-between;
    flex-wrap: wrap; gap: 12px; margin-bottom: 20px;
  }
  .produk-toolbar h6 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 22px; font-weight: 800; color: #8b5a2b !important; margin: 0;
    text-transform: uppercase; letter-spacing: 1px;
    border-left: 4px solid #d4af37; padding-left: 12px;
  }
  .search-box {
    position: relative;
  }
  .search-box input {
    border: 1.5px solid #e8eaf0; border-radius: 12px;
    padding: 10px 16px 10px 42px; font-size: 14px; width: 320px;
    outline: none; transition: all .3s ease;
  }
  .search-box input:focus { border-color: #d4af37; box-shadow: 0 0 0 4px rgba(212,175,55,0.15); }
  .search-box i {
    position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
    color: #b0b7c3; font-size: 14px;
  }

  /* ── Product Cards ── */
  .product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 20px;
  }
  .product-card {
    background: #fff;
    border-radius: 18px;
    border: 1px solid rgba(139, 90, 43, 0.12);
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,.04);
    transition: transform .25s ease, box-shadow .25s ease;
    animation: fadeUp .4s ease both;
  }
  .product-card:hover { transform: translateY(-5px); box-shadow: 0 16px 36px rgba(139,90,43,.15); }

  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .product-card:nth-child(1) { animation-delay: .05s }
  .product-card:nth-child(2) { animation-delay: .10s }
  .product-card:nth-child(3) { animation-delay: .15s }
  .product-card:nth-child(4) { animation-delay: .20s }
  .product-card:nth-child(5) { animation-delay: .25s }

  .card-img-wrap {
    height: 170px; background: linear-gradient(135deg, #fdfbf7 0%, #f4eee1 100%);
    display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
  }
  .card-img-wrap img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .35s ease;
  }
  .product-card:hover .card-img-wrap img { transform: scale(1.06); }
  .card-img-wrap i { font-size: 52px; color: #d4af37; }
  .img-fallback {
    width: 100%; height: 100%;
    align-items: center; justify-content: center;
  }

  .stock-badge {
    position: absolute; top: 12px; right: 12px;
    border-radius: 20px; padding: 4px 12px; font-size: 11px; font-weight: 700;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
  }
  .badge-ok   { background: #e6f9f0; color: #1db36e; border: 1px solid #1db36e; }
  .badge-low  { background: #fff4e0; color: #f0a500; border: 1px solid #f0a500; }
  .badge-out  { background: #fde8e8; color: #e53e3e; border: 1px solid #e53e3e; }

  .card-body-inner { padding: 18px 20px 20px; }
  .product-name {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 16px; font-weight: 700; color: #2b3445;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 6px;
  }
  .product-price {
    font-size: 20px; font-weight: 800; 
    background: linear-gradient(135deg, #d4af37, #b8860b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 0 2px 8px rgba(212,175,55,0.25);
    margin-bottom: 12px;
    letter-spacing: -0.5px;
  }
  .product-meta {
    display: flex; justify-content: space-between;
    font-size: 13px; font-weight: 600; color: #475569;
    border-top: 1px dashed rgba(139,90,43,0.2); padding-top: 12px; margin-top: 4px;
  }
  .product-meta span i { margin-right: 4px; }

  /* ── Empty state ── */
  .empty-state { text-align: center; padding: 60px 20px; color: #b0b7c3; }
  .empty-state i { font-size: 56px; margin-bottom: 12px; display: block; }
</style>

<div class="produk-wrap">

  <?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>
  <?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert"><i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
  <?php endif; ?>

  <?php if(session()->get('role') == 'admin'): ?>
  <div class="row g-4 mb-4">
    <div class="col-sm-4">
      <div class="stat-card blue">
        <div class="stat-icon"><i class="bi bi-box-seam"></i></div>
        <div>
          <div class="stat-label">Total Produk</div>
          <div class="stat-value"><?= count($produk) ?></div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="stat-card green">
        <div class="stat-icon"><i class="bi bi-stack"></i></div>
        <div>
          <div class="stat-label">Total Stok</div>
          <div class="stat-value"><?= array_sum(
              array_column($produk, "jumlah"),
          ) ?></div>
        </div>
      </div>
    </div>
    <div class="col-sm-4">
      <div class="stat-card amber">
        <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
        <div>
          <div class="stat-label">Stok Menipis</div>
          <div class="stat-value"><?= count(
              array_filter($produk, fn($p) => $p["jumlah"] <= 10),
          ) ?></div>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="produk-toolbar">
    <div class="search-box">
      <i class="bi bi-search"></i>
      <input type="text" id="searchInput" placeholder="Cari produk...">
    </div>
    
    <div class="d-flex flex-wrap gap-2 align-items-center">
      <?php if(session()->get('role') == 'admin'): ?>
      <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm text-white" data-bs-toggle="modal" data-bs-target="#tambahProdukModal" style="border-radius: 10px; padding: 8px 16px; font-size: 13px; font-weight: 600; border: none;">
        <i class="bi bi-plus-lg"></i> Tambah Produk Baru
      </button>
      <a type="button" class="btn btn-danger d-inline-flex align-items-center gap-1 text-white" href="<?= base_url('produk/export-pdf') ?>" target="_blank" style="border-radius: 10px; padding: 8px 16px; font-size: 13px; font-weight: 500;">
        <i class="bi bi-file-earmark-pdf"></i> Export PDF
      </a>
      
      <a type="button" class="btn btn-success d-inline-flex align-items-center gap-1 text-white" href="<?= base_url('produk/export-excel') ?>" style="border-radius: 10px; padding: 8px 16px; font-size: 13px; font-weight: 500;">
        <i class="bi bi-file-earmark-excel"></i> Export Excel
      </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Modal Tambah Produk -->
  <div class="modal fade" id="tambahProdukModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Produk Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="<?= base_url('produk') ?>" method="post" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nama Produk</label>
              <input type="text" class="form-control" name="nama" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Harga Jual (IDR)</label>
              <input type="text" class="form-control format-rupiah" required>
              <input type="hidden" name="harga">
            </div>
            <div class="mb-3">
              <label class="form-label">Harga Beli / Modal (IDR)</label>
              <input type="text" class="form-control format-rupiah" required>
              <input type="hidden" name="harga_beli">
            </div>
            <div class="mb-3">
              <label class="form-label">Jumlah Stok</label>
              <input type="number" class="form-control" name="jumlah" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Supplier (Opsional)</label>
              <select name="supplier_id" class="form-select">
                <option value="">-- Pilih Supplier --</option>
                <?php foreach($suppliers as $sup): ?>
                <option value="<?= $sup['id'] ?>"><?= esc($sup['nama']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Foto Produk (Opsional)</label>
              <input type="file" class="form-control" name="foto" accept="image/*">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan Produk</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php if (empty($produk)): ?>
    <div class="empty-state">
      <i class="bi bi-inbox"></i>
      <p>Belum ada produk tersedia.</p>
    </div>
  <?php else: ?>
  <div class="product-grid" id="productGrid">
    <?php foreach ($produk as $p): ?>
      <?php
      $stok = $p["jumlah"];
      if ($stok == 0) {
          $badgeClass = "badge-out";
          $badgeText = "Habis";
      } elseif ($stok <= 10) {
          $badgeClass = "badge-low";
          $badgeText = "Menipis";
      } else {
          $badgeClass = "badge-ok";
          $badgeText = "Tersedia";
      }
      ?>
      <div class="product-card" data-name="<?= strtolower(esc($p["nama"])) ?>">
        <div class="card-img-wrap">
          <?php if (!empty($p["foto"])): ?>
            <img src="<?= base_url(
                "NiceAdmin/assets/img/" . esc($p["foto"]),
            ) ?>"
                 alt="<?= esc($p["nama"]) ?>"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
            <div class="img-fallback" style="display:none"><i class="bi bi-bag-heart"></i></div>
          <?php else: ?>
            <i class="bi bi-bag-heart"></i>
          <?php endif; ?>
          <span class="stock-badge <?= $badgeClass ?>"><?= $badgeText ?></span>
        </div>
        <div class="card-body-inner">
          <div class="product-name"><?= esc($p["nama"]) ?></div>
          <div class="product-price">Rp <?= number_format(
              $p["harga"],
              0,
              ",",
              ".",
          ) ?></div>
          <div class="product-meta mb-3">
            <span><i class="bi bi-layers"></i> Stok: <?= $stok ?></span>
            <span><i class="bi bi-truck"></i> <?= !empty($p['supplier_nama']) ? esc($p['supplier_nama']) : 'Tanpa Supplier' ?></span>
          </div>

          <?php if(session()->get('role') == 'admin'): ?>
          <div class="d-flex gap-1 mb-2">
            <button type="button" class="btn btn-sm flex-grow-1 text-dark fw-bold" data-bs-toggle="modal" data-bs-target="#editModal<?= $p['id'] ?>" style="font-size: 0.75rem; background: linear-gradient(135deg, #fde047, #eab308); border: none; box-shadow: 0 4px 10px rgba(234,179,8,0.3);">
              <i class="bi bi-pencil"></i> Edit
            </button>
            <button type="button" class="btn btn-sm flex-grow-1 text-white" data-bs-toggle="modal" data-bs-target="#restockModal<?= $p['id'] ?>" style="font-size: 0.75rem; background: linear-gradient(135deg, #10b981, #059669); border: none;">
              <i class="bi bi-box-arrow-in-down"></i> Restock
            </button>
            <button type="button" class="btn btn-sm btn-danger text-white" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $p['id'] ?>" style="font-size: 0.75rem; border: none;">
              <i class="bi bi-trash"></i>
            </button>
          </div>
          <?php endif; ?>

          <?php if ($stok > 0): ?>
            <form action="<?= base_url('keranjang/add/' . $p['id']) ?>" method="post">
              <button type="submit" class="btn btn-sm btn-primary w-100"><i class="bi bi-cart-plus"></i> Masukkan Keranjang</button>
            </form>
          <?php else: ?>
            <button class="btn btn-sm btn-secondary w-100" disabled>Stok Habis</button>
          <?php endif; ?>
        </div>
      </div>

      <!-- Modal Edit Produk -->
      <div class="modal fade" id="editModal<?= $p['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Info Produk</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('produk/edit/' . $p['id']) ?>" method="post" enctype="multipart/form-data">
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label">Nama Produk</label>
                  <input type="text" class="form-control" name="nama" value="<?= esc($p['nama']) ?>" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Harga Jual (IDR)</label>
                  <input type="text" class="form-control format-rupiah" value="<?= (int)$p['harga'] ?>" required>
                  <input type="hidden" name="harga" value="<?= (int)$p['harga'] ?>">
                </div>
                <input type="hidden" name="harga_beli" value="<?= isset($p['harga_beli']) ? (int)$p['harga_beli'] : 0 ?>">
                <input type="hidden" name="jumlah" value="<?= $p['jumlah'] ?>">
                <div class="mb-3">
                  <label class="form-label">Supplier (Opsional)</label>
                  <select name="supplier_id" class="form-select">
                    <option value="">-- Pilih Supplier --</option>
                    <?php foreach($suppliers as $sup): ?>
                    <option value="<?= $sup['id'] ?>" <?= ($p['supplier_id'] == $sup['id']) ? 'selected' : '' ?>><?= esc($sup['nama']) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Ganti Foto Produk (Opsional)</label>
                  <input type="file" class="form-control" name="foto" accept="image/*">
                  <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto.</small>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Modal Restock -->
      <div class="modal fade" id="restockModal<?= $p['id'] ?>" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
              <h5 class="modal-title fw-bold" style="color: #8B5A2B;"><i class="bi bi-box-arrow-in-down me-2"></i>Pembelian ke Supplier</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('produk/restock/' . $p['id']) ?>" method="post">
              <div class="modal-body">
                <p class="text-muted small mb-4">Tambahkan stok fisik ke gudang. Transaksi ini akan otomatis dicatat sebagai <strong>Hutang ke Supplier</strong> berdasarkan Harga Beli yang Anda masukkan.</p>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Stok Fisik Saat Ini</label>
                  <input type="text" class="form-control bg-light" value="<?= $p['jumlah'] ?> pcs" readonly>
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Jumlah Tambah (pcs)</label>
                  <input type="number" class="form-control" name="jumlah_tambah" required min="1" placeholder="Berapa pcs yang dibeli?">
                </div>
                <div class="mb-3">
                  <label class="form-label fw-semibold">Harga Beli dari Supplier (Satuan IDR)</label>
                  <input type="text" class="form-control format-rupiah" value="<?= isset($p['harga_beli']) ? (int)$p['harga_beli'] : 0 ?>" required>
                  <input type="hidden" name="harga_beli_sekarang" value="<?= isset($p['harga_beli']) ? (int)$p['harga_beli'] : 0 ?>">
                  <small class="text-muted">Ganti harga ini jika harga modal dari supplier berubah.</small>
                </div>
              </div>
              <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius: 8px;">Batal</button>
                <button type="submit" class="btn btn-primary" style="border-radius: 8px;">Restock & Catat Hutang</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- Modal Hapus Produk -->
      <div class="modal fade" id="deleteModal<?= $p['id'] ?>" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
          <div class="modal-content border-0" style="background:#fff;border-radius:24px;padding:36px 32px;text-align:center;box-shadow:0 30px 60px rgba(0,0,0,.2);">
            <div class="modal-body p-0">
               <div style="width:68px;height:68px;border-radius:50%;background:rgba(220,38,38,.1);display:grid;place-items:center;margin:0 auto 18px">
                  <i class="bi bi-exclamation-circle" style="font-size:2rem;color:#dc2626"></i>
               </div>
               <h5 style="font-weight:800;color:#0f172a;margin-bottom:8px;font-size:1.2rem">Konfirmasi Hapus</h5>
               <p style="color:#64748b;font-size:14px;margin-bottom:28px;line-height:1.6">
                   Apakah Anda yakin ingin menghapus produk<br><strong class="text-dark"><?= esc($p['nama']) ?></strong>?
               </p>
               <div class="d-flex gap-3">
                 <button type="button" class="btn-logout-cancel" data-bs-dismiss="modal">
                     Batal
                 </button>
                 <a href="<?= base_url('produk/delete/' . $p['id']) ?>" class="btn-logout-confirm">
                     Ya, Hapus!
                 </a>
               </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

</div>

<script>
  document.getElementById('searchInput').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
      card.style.display = card.dataset.name.includes(q) ? '' : 'none';
    });
  });

  // Rupiah Formatter
  document.querySelectorAll('.format-rupiah').forEach(function(el) {
    // Initial formatting on load
    if (el.value) {
        let rawValue = el.value.replace(/[^0-9]/g, '');
        if(rawValue) {
           let formatted = new Intl.NumberFormat('id-ID').format(rawValue);
           el.value = 'Rp ' + formatted;
        }
    }

    // Formatting on type
    el.addEventListener('input', function(e) {
        let rawValue = this.value.replace(/[^0-9]/g, '');
        // Update the hidden input which is right after this element
        if(this.nextElementSibling) {
            this.nextElementSibling.value = rawValue;
        }
        
        if(rawValue) {
           let formatted = new Intl.NumberFormat('id-ID').format(rawValue);
           this.value = 'Rp ' + formatted;
        } else {
           this.value = '';
        }
    });
  });
</script>

<?= $this->endSection() ?>
