<?= $this->extend("layout") ?>
<?= $this->section("content") ?>

<?php if (session()->getFlashdata('success')) : ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
  <i class="bi bi-check-circle me-1"></i>
  <?= session()->getFlashdata('success') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <i class="bi bi-exclamation-octagon me-1"></i>
  <?= session()->getFlashdata('error') ?>
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<div class="card">
  <div class="card-body pt-3">
    <!-- Bordered Tabs -->
    <ul class="nav nav-tabs nav-tabs-bordered">

      <li class="nav-item">
        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#api-key">API Key</button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#alamat">Alamat Toko</button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#qris">QRIS Upload</button>
      </li>

      <li class="nav-item">
        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#logo-upload">Store Logo</button>
      </li>

    </ul>
    <div class="tab-content pt-2">

      <div class="tab-pane fade show active api-key" id="api-key">
        <h5 class="card-title">Api Key List</h5>
        <p>Manage API Keys for every services that you use.</p>

        <table class="table table-borderless table-striped mt-3">
          <thead>
            <tr>
              <th scope="col">API Name</th>
              <th scope="col">API Key</th>
              <th scope="col">Added</th>
              <th scope="col">Expires</th>
              <th scope="col">Total</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  1 &nbsp; Shipping Cost <i class="bi bi-chevron-right ms-auto"></i>
                </div>
              </td>
              <td><span class="text-danger"><i class="bi bi-eye-slash"></i> <?= esc($shipping_key) ?></span></td>
              <td><?= date('d/m/Y') ?></td>
              <td>-</td>
              <td>0 / 10</td>
            </tr>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  2 &nbsp; Payment API <i class="bi bi-chevron-right ms-auto"></i>
                </div>
              </td>
              <td><span class="text-danger"><i class="bi bi-eye-slash"></i> <?= esc($payment_key) ?></span></td>
              <td><?= date('d/m/Y') ?></td>
              <td>-</td>
              <td>0 / ~</td>
            </tr>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-2">
                  3 &nbsp; QRIS API <i class="bi bi-chevron-right ms-auto"></i>
                </div>
              </td>
              <td><span class="text-danger"><i class="bi bi-eye-slash"></i> <?= esc($qris_key) ?></span></td>
              <td><?= date('d/m/Y') ?></td>
              <td>-</td>
              <td>0 / ~</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="tab-pane fade" id="alamat">
        <h5 class="card-title">Pengaturan Alamat Toko Default</h5>
        <p>Alamat ini akan digunakan secara otomatis sebagai Lokasi Asal pengiriman saat Customer mengecek ongkos kirim.</p>
        
        <form action="<?= base_url('settings/update-address') ?>" method="post">
          <?= csrf_field() ?>
          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Alamat Saat Ini</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" value="<?= esc($store_address['name']) ?>" disabled style="background: #f8f9fa;">
            </div>
          </div>
          
          <div class="row mb-3">
            <label class="col-sm-3 col-form-label">Cari Alamat Baru (Kelurahan)</label>
            <div class="col-sm-9">
              <select class="form-select lokasi-select2" id="store_address_select" required>
                <option value="">Ketik Kelurahan Baru...</option>
              </select>
              <input type="hidden" name="store_address_id" id="store_address_id">
              <input type="hidden" name="store_address_name" id="store_address_name">
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-sm-9 offset-sm-3">
              <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Alamat</button>
            </div>
          </div>
        </form>
      </div>

      <div class="tab-pane fade qris" id="qris">
        <h5 class="card-title">Upload QRIS</h5>
        <p>Unggah gambar QRIS (opsional) untuk ditampilkan kepada pelanggan saat mereka melakukan pembayaran pesanan.</p>

        <form action="<?= base_url('settings/upload-qris') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="row mb-3">
            <label for="qris_image" class="col-sm-2 col-form-label">Gambar QRIS</label>
            <div class="col-sm-10">
              <input class="form-control" type="file" id="qris_image" name="qris_image" accept="image/*" required>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-10 offset-sm-2">
              <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i> Upload QRIS</button>
            </div>
          </div>
        </form>
        
        <?php if(file_exists(ROOTPATH . 'public/uploads/qris.png')): ?>
          <hr>
          <h6>QRIS Saat Ini:</h6>
          <img src="<?= base_url('uploads/qris.png?v=' . time()) ?>" alt="QRIS Aktif" style="max-width: 250px; border: 1px solid #ccc; border-radius: 8px;">
        <?php endif; ?>
      </div>

      <div class="tab-pane fade" id="logo-upload">
        <h5 class="card-title">Upload Logo Toko</h5>
        <p>Unggah gambar untuk mengubah logo utama toko Anda di seluruh halaman website.</p>

        <form action="<?= base_url('settings/upload-logo') ?>" method="post" enctype="multipart/form-data">
          <?= csrf_field() ?>
          <div class="row mb-3">
            <label for="logo_image" class="col-sm-2 col-form-label">Gambar Logo</label>
            <div class="col-sm-10">
              <input class="form-control" type="file" id="logo_image" name="logo_image" accept="image/*" required>
              <small class="text-muted">Gunakan gambar dengan format PNG (transparan disarankan) untuk hasil terbaik.</small>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-sm-10 offset-sm-2">
              <button type="submit" class="btn btn-primary"><i class="bi bi-upload me-1"></i> Update Logo</button>
            </div>
          </div>
        </form>
        
        <hr>
        <h6>Logo Saat Ini:</h6>
        <div class="p-3 bg-light rounded d-inline-block border">
            <img src="<?= base_url('NiceAdmin/assets/img/logo51.png?v=' . time()) ?>" alt="Logo Saat Ini" style="max-height: 80px;">
        </div>
      </div>

    </div><!-- End Bordered Tabs -->

  </div>
</div>

<!-- Select2 Script for Alamat Tab -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
    $('#store_address_select').select2({
        width: '100%',
        minimumInputLength: 3,
        language: {
            inputTooShort: function() { return 'Ketik minimal 3 huruf nama kelurahan...'; },
            noResults: function() { return 'Kelurahan tidak ditemukan'; },
            searching: function() { return 'Mencari...'; }
        },
        ajax: {
            url: '<?= base_url('ongkir/lokasi') ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { search: params.term };
            },
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return { id: item.id, text: item.label || item.name };
                    })
                };
            }
        }
    });

    $('#store_address_select').on('select2:select', function (e) {
        var data = e.params.data;
        $('#store_address_id').val(data.id);
        $('#store_address_name').val(data.text);
    });
});
</script>

<?= $this->endSection() ?>
