<?= $this->extend("layout") ?>
<?= $this->section("content") ?>

<!-- jQuery & Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

  .checkout-wrap { font-family: 'Plus Jakarta Sans', sans-serif; }
  .checkout-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 22px; color: #1a1d2e; font-weight: 700;
    margin-bottom: 24px;
  }
  
  .checkout-card {
    border: none; border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,.04);
    background: #fff; overflow: hidden;
  }

  .form-label { font-weight: 600; color: #4a5568; font-size: 14px; }
  .form-control, .form-select {
    border-radius: 10px; border: 1.5px solid #e2e8f0;
    padding: 10px 14px; font-size: 14px;
    transition: all .2s;
  }
  .form-control:focus, .form-select:focus {
    border-color: #3b6ef8; box-shadow: 0 0 0 4px rgba(59,110,248,.1);
  }

  .summary-table th { color: #8a94a6; font-size: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; }
  .summary-table td { padding-top: 12px; padding-bottom: 12px; font-size: 14px; border-bottom: 1px dashed #edf0f5; }
  
  .total-row {
    background: linear-gradient(135deg, #f4f6ff 0%, #e8f0fe 100%);
    border-radius: 16px; padding: 20px; margin-top: 20px;
  }
  .select2-container--default .select2-selection--single {
    border-radius: 10px; border: 1.5px solid #e2e8f0; height: 44px;
  }
  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 42px; color: #4a5568; padding-left: 14px;
  }
  .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 42px; right: 10px;
  }
  .select2-container { width: 100% !important; }
</style>

<div class="row checkout-wrap">
  <div class="col-lg-7">
    <div class="card checkout-card mb-4">
      <div class="card-body p-4 p-md-5">
        <h5 class="checkout-title">Formulir Pengiriman</h5>
        
        <form action="<?= base_url('buy') ?>" method="post" id="checkoutForm">
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" name="username" value="<?= session()->get('username') ?>" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea class="form-control" name="alamat" rows="2" required></textarea>
          </div>
          <div class="mb-3">
            <label for="kelurahan" class="form-label">Kelurahan</label>
            <select class="form-select" id="kelurahan" name="kelurahan" required>
                <option value="">Cari Kelurahan...</option>
            </select>
            <input type="hidden" name="kelurahan_nama" id="kelurahan_nama">
          </div>
          <div class="mb-3">
            <label for="layanan" class="form-label">Layanan</label>
            <select class="form-select" id="layanan" name="layanan" required>
                <option value="">Pilih Layanan...</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Ongkir</label>
            <input type="number" class="form-control" name="ongkir" id="ongkirInput" value="0" readonly>
          </div>
        </form>

      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="card checkout-card">
      <div class="card-body p-4 p-md-5">
        <h5 class="checkout-title">Ringkasan Pesanan</h5>
        
        <div class="table-responsive">
          <table class="table table-borderless">
            <thead>
              <tr class="border-bottom">
                <th scope="col">Nama</th>
                <th scope="col">Harga</th>
                <th scope="col">Jumlah</th>
                <th scope="col" class="text-end">Sub Total</th>
              </tr>
            </thead>
            <tbody>
              <?php 
                $subtotal_all = 0;
                if(!empty($cart)):
                  foreach($cart as $item): 
                    $subtotal = $item['harga'] * $item['qty'];
                    $subtotal_all += $subtotal;
              ?>
              <tr>
                <td><?= esc($item['nama']) ?></td>
                <td>IDR <?= number_format($item['harga'], 0, ',', ',') ?></td>
                <td><?= $item['qty'] ?></td>
                <td class="text-end">IDR <?= number_format($subtotal, 0, ',', ',') ?></td>
              </tr>
              <?php 
                  endforeach; 
                else:
              ?>
                <tr><td colspan="4" class="text-center text-muted">Keranjang Kosong</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <div class="total-row mt-4">
          <div class="d-flex justify-content-between mb-2">
            <span class="text-muted fw-bold">Subtotal</span>
            <span class="fw-bold" id="subtotalValue" data-value="<?= $subtotal_all ?>">IDR <?= number_format($subtotal_all, 0, ',', '.') ?></span>
          </div>
          <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
            <span class="text-muted fw-bold">Ongkos Kirim</span>
            <span class="fw-bold" id="ongkirDisplayValue">IDR 0</span>
          </div>
          <div class="d-flex justify-content-between mb-4">
            <span class="text-muted fw-bold">Total Pembayaran</span>
            <span class="fw-bold fs-4" id="totalValue" style="font-family: 'Plus Jakarta Sans', sans-serif; color: #3b6ef8 !important;">IDR <?= number_format($subtotal_all, 0, ',', '.') ?></span>
          </div>
          
          <button type="submit" form="checkoutForm" class="btn btn-primary w-100 py-3 fw-bold" style="border-radius: 12px; font-size: 15px;" <?= empty($cart) ? 'disabled' : '' ?>>
            <i class="bi bi-shield-lock me-2"></i> Buat Pesanan Sekarang
          </button>
        </div>

      </div>
    </div>
  </div>
</div>

<script>
function updateTotal() {
    let subtotal = parseInt(document.getElementById('subtotalValue').getAttribute('data-value'));
    let ongkir = parseInt(document.getElementById('ongkirInput').value) || 0;
    let total = subtotal + ongkir;
    
    // Format ke currency style
    let ongkirFormatted = ongkir.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    let totalFormatted = total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    
    document.getElementById('ongkirDisplayValue').innerText = 'IDR ' + ongkirFormatted;
    document.getElementById('totalValue').innerText = 'IDR ' + totalFormatted;
}

$(document).ready(function() {
    $('#layanan').select2({
        placeholder: 'Pilih Layanan...',
        disabled: true,
        width: '100%'
    });

    $('#kelurahan').select2({
        placeholder: 'Cari Kelurahan...',
        width: '100%',
        minimumInputLength: 3,
        language: {
            inputTooShort: function() {
                return 'Ketik minimal 3 huruf nama kelurahan...';
            },
            noResults: function() {
                return 'Kelurahan tidak ditemukan';
            },
            searching: function() {
                return 'Mencari...';
            }
        },
        ajax: {
            url: '<?= base_url('get-location') ?>',
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

    $('#kelurahan').on('select2:select', function (e) {
        var data = e.params.data;
        $('#kelurahan_nama').val(data.text);
    });

    $('#kelurahan').on('change', function() {
        var destination = $(this).val();
        $('#layanan').empty().trigger('change');
        $('#ongkirInput').val(0);
        updateTotal();
        
        if (destination) {
            $('#layanan').prop('disabled', false); // Enable select2
            $.ajax({
                url: '<?= base_url('get-cost') ?>',
                type: 'GET',
                data: { destination: destination },
                dataType: 'json',
                success: function(data) {
                    var options = [];
                    // Append default empty option
                    options.push({id: '', text: 'Pilih Layanan...'});
                    
                    if(data && data.length > 0) {
                        // Check if data is array of services (Komerce API format) or RajaOngkir standard
                        let costs = data[0].costs ? data[0].costs : data;
                        $.each(costs, function(index, item) {
                            let itemCost = item.cost;
                            let etd = item.etd || item.estimasi || '';
                            let serviceName = item.service || item.name;
                            let desc = item.description || '';
                            
                            // Handle RajaOngkir standard where cost is an array
                            if (Array.isArray(itemCost)) {
                                etd = itemCost[0].etd;
                                itemCost = itemCost[0].value;
                            }

                            options.push({
                                id: serviceName,
                                text: serviceName + ' (' + desc + ') - Estimasi ' + etd + ' Hari',
                                cost: itemCost
                            });
                        });
                    }
                    
                    $('#layanan').select2({
                        data: options,
                        width: '100%'
                    });
                }
            });
        }
    });

    $('#layanan').on('change', function() {
        var data = $(this).select2('data')[0];
        if (data && data.cost) {
            $('#ongkirInput').val(data.cost);
            updateTotal();
        } else {
            $('#ongkirInput').val(0);
            updateTotal();
        }
    });
});
</script>

<?= $this->endSection() ?>
