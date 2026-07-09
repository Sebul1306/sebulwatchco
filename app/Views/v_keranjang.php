<?= $this->extend("layout") ?>
<?= $this->section("content") ?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap');

  .cart-wrap { font-family: 'Plus Jakarta Sans', sans-serif; }
  .cart-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 24px; color: #1a1d2e; font-weight: 700;
    margin-bottom: 24px;
  }
  
  .cart-card {
    border: 2px solid rgba(212, 175, 55, 0.3);
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0,0,0,.04);
    background: #fff; overflow: hidden;
  }
  
  .table-cart th {
    background: #f8f9fa; color: #334155; font-size: 13px;
    text-transform: uppercase; letter-spacing: 1px; font-weight: 700;
    border-bottom: 2px solid #edf0f5; padding: 16px;
  }
  .table-cart td { vertical-align: middle; padding: 16px; border-bottom: 1px solid #f4f5f8; }
  
  .item-img {
    width: 70px; height: 70px; border-radius: 12px; object-fit: cover;
    box-shadow: 0 4px 10px rgba(0,0,0,.05);
  }
  .item-name { font-weight: 600; color: #1a1d2e; font-size: 15px; }
  .item-price { color: #3b6ef8; font-size: 14px; font-weight: 600; }
  
  .qty-input {
    width: 80px; border-radius: 8px; border: 1px solid #e2e8f0;
    text-align: center; font-weight: 600;
  }
  .qty-input:focus { border-color: #3b6ef8; box-shadow: 0 0 0 3px rgba(59,110,248,.1); outline: none; }
  
  .btn-remove {
    background: #fff0f0; color: #ff4d4f; border: none;
    width: 36px; height: 36px; border-radius: 10px;
    display: inline-flex; align-items: center; justify-content: center;
    transition: all .2s;
  }
  .btn-remove:hover { background: #ff4d4f; color: #fff; transform: translateY(-2px); }

  .empty-cart { text-align: center; padding: 60px 20px; }
  .empty-cart i { font-size: 64px; color: #dbe0e8; margin-bottom: 16px; display: block; }
  .empty-cart h5 { font-weight: 700; color: #1a1d2e; margin-bottom: 8px; }
  .empty-cart p { color: #8a94a6; margin-bottom: 24px; }
  
  .cart-summary {
    background: linear-gradient(135deg, #f4f6ff 0%, #e8f0fe 100%);
    border-radius: 16px; padding: 24px; margin-top: 24px;
    display: flex; justify-content: space-between; align-items: center;
  }
  .summary-label { font-size: 14px; color: #5a667b; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
  .summary-value { font-size: 28px; font-weight: 700; color: #3b6ef8; font-family: 'Plus Jakarta Sans', sans-serif; }
</style>

<div class="cart-wrap">
  <div class="cart-card card">
    <div class="card-body p-4 p-md-5 pt-3 pt-md-4">
    <?php if(session()->getFlashdata('success')): ?>
      <div class="alert alert-success alert-dismissible fade show"><i class="bi bi-check-circle me-2"></i><?= session()->getFlashdata('success') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
      <div class="alert alert-danger alert-dismissible fade show"><i class="bi bi-exclamation-triangle me-2"></i><?= session()->getFlashdata('error') ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <form action="<?= base_url('keranjang/update') ?>" method="post">
      <div class="table-responsive">
        <table class="table table-cart table-borderless">
          <thead>
            <tr>
              <th scope="col">Produk</th>
              <th scope="col">Harga</th>
              <th scope="col" style="width: 120px;" class="text-center">Jumlah</th>
              <th scope="col">Subtotal</th>
              <th scope="col" class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $total = 0;
              if(!empty($cart)): 
                foreach($cart as $item): 
                  $subtotal = $item['harga'] * $item['qty'];
                  $total += $subtotal;
            ?>
            <tr>
              <td>
                <div class="d-flex align-items-center gap-3">
                  <img src="<?= base_url('NiceAdmin/assets/img/' . (isset($item['foto']) && $item['foto'] ? $item['foto'] : 'product-1.jpg')) ?>" alt="Foto" class="item-img">
                  <div class="item-name"><?= esc($item['nama']) ?></div>
                </div>
              </td>
              <td class="item-price align-middle">IDR <?= number_format($item['harga'], 0, ',', '.') ?></td>
              <td class="align-middle">
                <div class="d-flex justify-content-center align-items-center bg-light rounded-3 p-1" style="width: 110px; border: 1px solid #e2e8f0;">
                  <button type="button" class="btn btn-sm btn-light border-0 text-secondary fw-bold btn-qty" data-action="minus" data-id="<?= $item['id'] ?>"><i class="bi bi-dash"></i></button>
                  <input type="number" class="form-control border-0 bg-transparent text-center px-0 fw-bold qty-input-val" name="qty[<?= $item['id'] ?>]" id="qty-<?= $item['id'] ?>" value="<?= $item['qty'] ?>" min="1" data-price="<?= $item['harga'] ?>" data-id="<?= $item['id'] ?>" style="width: 40px; font-size: 15px; box-shadow: none;">
                  <button type="button" class="btn btn-sm btn-light border-0 text-secondary fw-bold btn-qty" data-action="plus" data-id="<?= $item['id'] ?>"><i class="bi bi-plus"></i></button>
                </div>
              </td>
              <td class="align-middle fw-bold text-dark subtotal-val" id="subtotal-<?= $item['id'] ?>">IDR <?= number_format($subtotal, 0, ',', '.') ?></td>
              <td class="text-center align-middle">
                <a href="<?= base_url('keranjang/remove/' . $item['id']) ?>" class="btn-remove">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
            <?php 
                endforeach;
              else:
            ?>
            <tr>
              <td colspan="5">
                <div class="empty-cart">
                  <i class="bi bi-cart-x"></i>
                  <h5>Keranjang Masih Kosong</h5>
                  <p>Yuk, temukan barang impianmu di katalog produk kami!</p>
                  <a href="<?= base_url('produk') ?>" class="btn btn-primary px-4 py-2" style="border-radius: 10px;">Belanja Sekarang</a>
                </div>
              </td>
            </tr>
            <?php endif; ?>
          </tbody>
          </tbody>
        </table>
      </div>

      <?php if (!empty($cart)): ?>
      <div class="cart-summary">
        <div>
          <div class="summary-label">Total Belanja</div>
          <div class="summary-value" id="cart-total-val">IDR <?= number_format($total, 0, ',', '.') ?></div>
        </div>
      </div>
      <?php endif; ?>

      <div class="mt-4 d-flex gap-2 flex-wrap justify-content-end">
        <?php if (!empty($cart)): ?>
          <a class="btn btn-outline-danger px-4 py-2" href="<?= base_url('keranjang/clear') ?>" style="border-radius: 10px; font-weight: 500;">Kosongkan Keranjang</a>
          <button type="submit" class="btn btn-outline-primary px-4 py-2" style="border-radius: 10px; font-weight: 500;">Perbarui Keranjang</button>
          <a class="btn btn-primary px-5 py-2" href="<?= base_url('checkout') ?>" style="border-radius: 10px; font-weight: 600;">Checkout <i class="bi bi-arrow-right ms-2"></i></a>
        <?php endif; ?>
      </div>
    </form>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formatIDR = (number) => {
        // Mengubah format number ke format IDR yang sesuai dengan PHP number_format($num, 0, ',', '.')
        let numStr = Math.round(number).toString();
        let sisa = numStr.length % 3;
        let rupiah = numStr.substr(0, sisa);
        let ribuan = numStr.substr(sisa).match(/\d{3}/g);
        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'IDR ' + rupiah;
    };

    const calculateTotal = () => {
        let total = 0;
        document.querySelectorAll('.qty-input-val').forEach(input => {
            const qty = parseInt(input.value) || 0;
            const price = parseInt(input.dataset.price) || 0;
            total += qty * price;
        });
        const totalEl = document.getElementById('cart-total-val');
        if (totalEl) totalEl.innerText = formatIDR(total);
    };

    const autoUpdateCart = () => {
        const form = document.querySelector('form[action="<?= base_url('keranjang/update') ?>"]');
        const formData = new FormData(form);
        fetch('<?= base_url('keranjang/update') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).catch(err => console.error('Error auto-updating cart:', err));
    };

    document.querySelectorAll('.btn-qty').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.dataset.id;
            const action = this.dataset.action;
            const input = document.getElementById('qty-' + id);
            let currentVal = parseInt(input.value) || 1;
            
            if (action === 'plus') {
                currentVal++;
            } else if (action === 'minus' && currentVal > 1) {
                currentVal--;
            }
            
            input.value = currentVal;
            
            // Update Subtotal
            const price = parseInt(input.dataset.price);
            const subtotalEl = document.getElementById('subtotal-' + id);
            if (subtotalEl) subtotalEl.innerText = formatIDR(currentVal * price);
            
            calculateTotal();
            autoUpdateCart(); // Auto save to backend
        });
    });

    document.querySelectorAll('.qty-input-val').forEach(input => {
        input.addEventListener('change', function() {
            let val = parseInt(this.value);
            if (isNaN(val) || val < 1) {
                val = 1;
                this.value = 1;
            }
            const id = this.dataset.id;
            const price = parseInt(this.dataset.price);
            const subtotalEl = document.getElementById('subtotal-' + id);
            if (subtotalEl) subtotalEl.innerText = formatIDR(val * price);
            
            calculateTotal();
            autoUpdateCart(); // Auto save to backend
        });
    });
});
</script>

<?= $this->endSection() ?>
