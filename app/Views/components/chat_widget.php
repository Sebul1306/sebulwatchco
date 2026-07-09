<!-- ======= Floating Chat Widget ======= -->
<?php
$isAdmin = (session()->get('role') == 'admin');
$currentUserId = session()->get('id') ?? 0;
?>

<style>
/* Floating Chat Button */
.chat-fab {
  position: fixed;
  bottom: 30px;
  right: 30px;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%);
  color: #fff;
  border: none;
  box-shadow: 0 6px 20px rgba(139,90,43, 0.45);
  cursor: pointer;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}
.chat-fab:hover {
  transform: scale(1.1) translateY(-2px);
  box-shadow: 0 10px 30px rgba(139,90,43, 0.55);
}
.chat-fab .badge-dot {
  position: absolute;
  top: 6px;
  right: 6px;
  width: 14px;
  height: 14px;
  background: #dc3545;
  border: 2px solid #fff;
  border-radius: 50%;
  display: none;
  font-size: 0;
}

/* Chat Panel */
.chat-panel {
  position: fixed;
  bottom: 100px;
  right: 30px;
  width: 400px;
  max-height: 600px;
  background: #fff;
  border-radius: 20px;
  box-shadow: 0 15px 60px rgba(0,0,0,0.15), 0 5px 20px rgba(0,0,0,0.08);
  z-index: 9999;
  display: none;
  flex-direction: column;
  overflow: hidden;
  animation: chatSlideUp 0.35s cubic-bezier(0.25, 0.8, 0.25, 1);
}
.chat-panel.open { display: flex; }

@keyframes chatSlideUp {
  from { opacity: 0; transform: translateY(20px) scale(0.95); }
  to   { opacity: 1; transform: translateY(0) scale(1); }
}

/* Chat Header */
.chat-panel-header {
  background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%);
  color: #fff;
  padding: 16px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-shrink: 0;
}
.chat-panel-header .chat-header-info {
  display: flex;
  align-items: center;
  gap: 12px;
}
.chat-panel-header .chat-avatar {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  background: rgba(255,255,255,0.2);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 18px;
}
.chat-panel-header .chat-header-text h6 {
  margin: 0;
  font-size: 15px;
  font-weight: 700;
  color: #fff !important;
}
.chat-panel-header .chat-header-text small {
  opacity: 0.85;
  font-size: 11px;
}
.chat-panel-header .chat-header-actions button {
  background: none;
  border: none;
  color: rgba(255,255,255,0.7);
  font-size: 18px;
  cursor: pointer;
  padding: 4px 6px;
  border-radius: 6px;
  transition: all 0.2s;
}
.chat-panel-header .chat-header-actions button:hover {
  color: #fff;
  background: rgba(255,255,255,0.15);
}

/* Customer List (Admin) */
.chat-customer-list {
  height: 420px;
  overflow-y: auto;
  padding: 10px;
}
.chat-customer-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 14px;
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
}
.chat-customer-item:hover {
  background: #f0fdf4;
}
.chat-customer-item .cust-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 15px;
  flex-shrink: 0;
}
.chat-customer-item .cust-name {
  font-weight: 600;
  font-size: 14px;
  color: #2b3445;
  flex: 1;
}
.chat-customer-item .cust-unread {
  background: #dc3545;
  color: #fff;
  font-size: 11px;
  font-weight: 700;
  padding: 2px 6px;
  border-radius: 10px;
  min-width: 20px;
  text-align: center;
}

/* Chat Messages Area */
.chat-messages {
  flex: 1;
  overflow-y: auto;
  padding: 16px;
  background: #f8fafb;
  min-height: 300px;
  height: 350px;
}
.chat-messages::-webkit-scrollbar { width: 4px; }
.chat-messages::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

.chat-msg-row {
  display: flex;
  margin-bottom: 8px;
}
.chat-msg-row.align-right { justify-content: flex-end; }
.chat-msg-row.align-left { justify-content: flex-start; }

.chat-bubble-widget {
  max-width: 85%;
  padding: 10px 14px;
  border-radius: 16px;
  font-size: 13px;
  line-height: 1.5;
  word-wrap: break-word;
  position: relative;
}
/* Brown Theme (for Customer) */
.chat-bubble-widget.theme-brown {
  background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%);
  color: #fff;
}
.chat-msg-row.align-right .chat-bubble-widget.theme-brown { border-bottom-right-radius: 4px; }
.chat-msg-row.align-left .chat-bubble-widget.theme-brown { border-bottom-left-radius: 4px; }

/* White Theme (for Admin) */
.chat-bubble-widget.theme-white {
  background: #fff;
  color: #333;
  box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}
.chat-msg-row.align-right .chat-bubble-widget.theme-white { border-bottom-right-radius: 4px; }
.chat-msg-row.align-left .chat-bubble-widget.theme-white { border-bottom-left-radius: 4px; }

.chat-bubble-time {
  font-size: 10px;
  margin-top: 3px;
  opacity: 0.6;
}
.chat-bubble-widget.theme-brown .chat-bubble-time { color: rgba(255,255,255,0.7); }
.chat-bubble-widget.theme-white .chat-bubble-time { color: #999; }

/* Product Attachment inside Bubble */
.chat-product-card {
  border-radius: 8px;
  padding: 8px;
  display: flex;
  gap: 10px;
  margin-bottom: 6px;
  align-items: center;
  box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.chat-bubble-widget.theme-brown .chat-product-card {
  background: #fff;
}
.chat-bubble-widget.theme-white .chat-product-card {
  background: #f8f9fa;
}
.chat-product-card img {
  width: 50px;
  height: 50px;
  object-fit: contain;
  border-radius: 6px;
}
.chat-product-card-info {
  flex: 1;
}
.chat-product-card-name {
  font-weight: 600;
  font-size: 12px;
  color: #333;
  line-height: 1.2;
  margin-bottom: 4px;
}
.chat-product-card-price {
  color: #8B5A2B;
  font-weight: 700;
  font-size: 12px;
}

/* Chat Input */
.chat-input-wrapper {
  background: #fff;
  border-top: 1px solid #f1f5f9;
  display: flex;
  flex-direction: column;
}
.chat-attached-product {
  padding: 10px 16px;
  background: #f8fafb;
  border-bottom: 1px solid #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: space-between;
  display: none;
}
.chat-attached-product-info {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 12px;
  font-weight: 600;
  color: #333;
}
.chat-attached-product-info img {
  width: 30px;
  height: 30px;
  object-fit: contain;
  border-radius: 4px;
}
.chat-attached-product-remove {
  background: none;
  border: none;
  color: #dc3545;
  cursor: pointer;
}

.chat-input-bar {
  padding: 10px 12px;
  display: flex;
  gap: 8px;
  align-items: center;
}
.chat-input-bar .btn-attach {
  background: none;
  border: none;
  color: #64748b;
  font-size: 20px;
  cursor: pointer;
  padding: 4px;
  border-radius: 50%;
  transition: all 0.2s;
}
.chat-input-bar .btn-attach:hover {
  background: #f1f5f9;
  color: #8B5A2B;
}
.chat-input-bar input {
  flex: 1;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 8px 16px;
  font-size: 13px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  outline: none;
  transition: border-color 0.2s;
}
.chat-input-bar input:focus {
  border-color: #8B5A2B;
}
.chat-input-bar button.btn-send {
  width: 38px;
  height: 38px;
  border-radius: 50%;
  border: none;
  background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%);
  color: #fff;
  font-size: 15px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s;
  flex-shrink: 0;
}
.chat-input-bar button.btn-send:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(139,90,43,0.35);
}

/* Product Picker Overlay */
.chat-product-picker {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 350px;
  background: #fff;
  z-index: 100;
  display: none;
  flex-direction: column;
  border-top-left-radius: 20px;
  border-top-right-radius: 20px;
  box-shadow: 0 -5px 20px rgba(0,0,0,0.1);
  transform: translateY(100%);
  transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}
.chat-product-picker.show {
  transform: translateY(0);
}
.chat-picker-header {
  padding: 12px 16px;
  border-bottom: 1px solid #f1f5f9;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-weight: 600;
}
.chat-picker-header button {
  background: none;
  border: none;
  color: #64748b;
  cursor: pointer;
}
.chat-picker-search {
  padding: 10px 16px;
  border-bottom: 1px solid #f1f5f9;
}
.chat-picker-search input {
  width: 100%;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  padding: 6px 12px;
  font-size: 13px;
  outline: none;
}
.chat-picker-list {
  flex: 1;
  overflow-y: auto;
  padding: 8px 16px;
}
.chat-picker-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 8px 0;
  border-bottom: 1px solid #f8fafb;
  cursor: pointer;
}
.chat-picker-item:hover {
  background: #f8fafb;
}
.chat-picker-item img {
  width: 40px;
  height: 40px;
  object-fit: contain;
  border-radius: 6px;
}
.chat-picker-info {
  flex: 1;
}
.chat-picker-name {
  font-size: 12px;
  font-weight: 600;
  color: #333;
}
.chat-picker-price {
  font-size: 12px;
  color: #8B5A2B;
  font-weight: 700;
}

/* Empty state */
.chat-empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #94a3b8;
  font-size: 13px;
  padding: 40px;
  text-align: center;
}
.chat-empty-state i {
  font-size: 40px;
  margin-bottom: 12px;
  color: #cbd5e1;
}

/* Quick Replies */
.chat-quick-replies {
  display: flex;
  gap: 8px;
  padding: 10px 16px 0;
  overflow-x: auto;
  background: #fff;
  border-top: 1px solid #f1f5f9;
  scrollbar-width: none; /* Firefox */
}
.chat-quick-replies::-webkit-scrollbar { display: none; /* Chrome */ }
.quick-reply-btn {
  background: #f0fdf4;
  border: 1px solid #dcfce7;
  color: #8B5A2B;
  border-radius: 20px;
  padding: 6px 12px;
  font-size: 11px;
  white-space: nowrap;
  cursor: pointer;
  transition: all 0.2s;
  font-weight: 500;
}
.quick-reply-btn:hover {
  background: #8B5A2B;
  color: #fff;
}

/* Back button */
.chat-back-btn {
  background: none;
  border: none;
  color: rgba(255,255,255,0.8);
  font-size: 18px;
  cursor: pointer;
  padding: 4px 6px;
  border-radius: 6px;
  transition: all 0.2s;
  display: none;
}
.chat-back-btn:hover { color: #fff; background: rgba(255,255,255,0.15); }

@media (max-width: 480px) {
  .chat-panel {
    width: calc(100vw - 20px);
    right: 10px;
    bottom: 80px;
    max-height: 70vh;
  }
}
</style>

<!-- Floating Action Button -->
<button class="chat-fab" id="chatFab" title="Chat & Dukungan">
  <i class="bi bi-chat-dots-fill"></i>
  <span class="badge-dot" id="chatBadgeDot"></span>
</button>

<!-- Chat Panel -->
<div class="chat-panel" id="chatPanel">
  <!-- Header -->
  <div class="chat-panel-header">
    <div class="chat-header-info">
      <button class="chat-back-btn" id="chatBackBtn" title="Kembali">
        <i class="bi bi-arrow-left"></i>
      </button>
      <div class="chat-avatar">
        <i class="bi bi-chat-left-text-fill"></i>
      </div>
      <div class="chat-header-text">
        <h6 id="chatHeaderTitle"><?= $isAdmin ? 'Chat Pelanggan' : 'Chat Admin' ?></h6>
        <small id="chatHeaderSub"><?= $isAdmin ? 'Pilih pelanggan untuk membalas' : 'Sebul Watch Co. · Online' ?></small>
      </div>
    </div>
    <div class="chat-header-actions">
      <button id="chatCloseBtn" title="Tutup"><i class="bi bi-x-lg"></i></button>
    </div>
  </div>

  <!-- Body: Customer List (admin) or Messages (pelanggan) -->
  <div id="chatBody" style="position:relative; flex:1; display:flex; flex-direction:column; overflow:hidden;">
    <?php if ($isAdmin): ?>
      <!-- Admin: customer list loaded via AJAX -->
      <div class="chat-customer-list" id="chatCustomerList">
        <div class="chat-empty-state">
          <i class="bi bi-chat-left-text"></i>
          <span>Memuat daftar pelanggan...</span>
        </div>
      </div>
    <?php else: ?>
      <!-- Pelanggan: chat langsung -->
      <div class="chat-messages" id="chatMessages">
        <div class="chat-empty-state">
          <i class="bi bi-chat-left-text"></i>
          <span>Mulai percakapan dengan Admin!</span>
        </div>
      </div>
    <?php endif; ?>

    <!-- Product Picker Overlay -->
    <div class="chat-product-picker" id="chatProductPicker">
      <div class="chat-picker-header">
        <span>Pilih Produk</span>
        <button id="chatPickerClose"><i class="bi bi-x-lg"></i></button>
      </div>
      <div class="chat-picker-search">
        <input type="text" id="chatPickerSearch" placeholder="Cari produk...">
      </div>
      <div class="chat-picker-list" id="chatPickerList">
        <div class="text-center text-muted mt-4" style="font-size:12px;">Memuat produk...</div>
      </div>
    </div>
  </div>

  <!-- Chat Input -->
  <div class="chat-input-wrapper" id="chatInputWrapper" style="<?= $isAdmin ? 'display:none;' : '' ?>">
    <!-- Quick Replies (Customer Only) -->
    <?php if (!$isAdmin): ?>
    <div class="chat-quick-replies" id="chatQuickReplies">
      <button class="quick-reply-btn">Min, barang ini ready?</button>
      <button class="quick-reply-btn">Bisa dikirim hari ini?</button>
      <button class="quick-reply-btn">Apakah ada garansi?</button>
      <button class="quick-reply-btn">Lokasi toko di mana?</button>
    </div>
    <?php endif; ?>

    <!-- Attached product preview -->
    <div class="chat-attached-product" id="chatAttachedProduct">
      <div class="chat-attached-product-info">
        <img src="" id="chatAttachedImg" alt="">
        <div>
          <div id="chatAttachedName"></div>
          <div style="color:#8B5A2B" id="chatAttachedPrice"></div>
        </div>
      </div>
      <button class="chat-attached-product-remove" id="chatAttachedRemove" title="Batal Lampirkan">
        <i class="bi bi-x-circle-fill"></i>
      </button>
    </div>

    <!-- Input bar -->
    <div class="chat-input-bar">
      <!-- Attach button -->
      <button class="btn-attach" id="chatBtnAttach" title="Lampirkan Produk">
        <i class="bi bi-bag-plus"></i>
      </button>
      <input type="text" id="chatMsgInput" placeholder="Ketik pesan..." autocomplete="off">
      <button class="btn-send" id="chatSendBtn" title="Kirim">
        <i class="bi bi-send-fill"></i>
      </button>
    </div>
  </div>
</div>

<script>
(function() {
  const isAdmin = <?= $isAdmin ? 'true' : 'false' ?>;
  const currentUserId = <?= $currentUserId ?>;
  const baseUrl = '<?= base_url() ?>';
  let targetId = <?= (!$isAdmin) ? '1' : 'null' ?>;
  let chatOpen = false;
  let pollInterval = null;
  let attachedProductId = null;
  
  // Format Rupiah
  const formatRupiah = (angka) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
  };

  const fab = document.getElementById('chatFab');
  const panel = document.getElementById('chatPanel');
  const closeBtn = document.getElementById('chatCloseBtn');
  const backBtn = document.getElementById('chatBackBtn');
  const sendBtn = document.getElementById('chatSendBtn');
  const msgInput = document.getElementById('chatMsgInput');
  const inputWrapper = document.getElementById('chatInputWrapper');
  const badgeDot = document.getElementById('chatBadgeDot');
  const headerTitle = document.getElementById('chatHeaderTitle');
  const headerSub = document.getElementById('chatHeaderSub');

  // Product Attach Elements
  const btnAttach = document.getElementById('chatBtnAttach');
  const productPicker = document.getElementById('chatProductPicker');
  const pickerClose = document.getElementById('chatPickerClose');
  const pickerSearch = document.getElementById('chatPickerSearch');
  const pickerList = document.getElementById('chatPickerList');
  
  const attachedContainer = document.getElementById('chatAttachedProduct');
  const attachedImg = document.getElementById('chatAttachedImg');
  const attachedName = document.getElementById('chatAttachedName');
  const attachedPrice = document.getElementById('chatAttachedPrice');
  const attachedRemove = document.getElementById('chatAttachedRemove');

  // Toggle panel
  fab.addEventListener('click', function() {
    chatOpen = !chatOpen;
    panel.classList.toggle('open', chatOpen);
    if (chatOpen) {
      if (isAdmin) {
        if (!targetId) {
          loadCustomerList();
          startAdminPolling();
        } else {
          loadMessages();
          startPolling();
        }
      } else {
        loadMessages();
        startPolling();
      }
    } else {
      stopPolling();
      stopAdminPolling();
      productPicker.style.display = 'none';
      productPicker.classList.remove('show');
    }
  });

  closeBtn.addEventListener('click', function() {
    chatOpen = false;
    panel.classList.remove('open');
    stopPolling();
    stopAdminPolling();
    productPicker.style.display = 'none';
    productPicker.classList.remove('show');
  });

  // Admin: Back to customer list
  backBtn.addEventListener('click', function() {
    targetId = null;
    backBtn.style.display = 'none';
    headerTitle.textContent = 'Chat Pelanggan';
    headerSub.textContent = 'Pilih pelanggan untuk membalas';
    inputWrapper.style.display = 'none';
    stopPolling();
    loadCustomerList();
    startAdminPolling();
  });

  // Product Picker Logic
  btnAttach.addEventListener('click', function() {
    productPicker.style.display = 'flex';
    setTimeout(() => productPicker.classList.add('show'), 10);
    loadProducts();
  });

  pickerClose.addEventListener('click', function() {
    productPicker.classList.remove('show');
    setTimeout(() => productPicker.style.display = 'none', 300);
  });

  pickerSearch.addEventListener('keyup', function(e) {
    if (e.key === 'Enter') loadProducts(this.value);
  });

  function loadProducts(keyword = '') {
    pickerList.innerHTML = '<div class="text-center text-muted mt-4" style="font-size:12px;">Memuat produk...</div>';
    fetch(baseUrl + 'chat/getProducts?q=' + encodeURIComponent(keyword))
      .then(r => r.json())
      .then(data => {
        if (!data.products || data.products.length === 0) {
          pickerList.innerHTML = '<div class="text-center text-muted mt-4" style="font-size:12px;">Produk tidak ditemukan</div>';
          return;
        }
        let html = '';
        data.products.forEach(p => {
          html += `<div class="chat-picker-item" data-id="${p.id}" data-name="${p.nama}" data-price="${p.harga}" data-img="${p.foto}">
                     <img src="${baseUrl}NiceAdmin/assets/img/${p.foto}" alt="">
                     <div class="chat-picker-info">
                       <div class="chat-picker-name">${p.nama}</div>
                       <div class="chat-picker-price">${formatRupiah(p.harga)}</div>
                     </div>
                   </div>`;
        });
        pickerList.innerHTML = html;

        pickerList.querySelectorAll('.chat-picker-item').forEach(item => {
          item.addEventListener('click', function() {
            attachedProductId = this.dataset.id;
            attachedImg.src = baseUrl + 'NiceAdmin/assets/img/' + this.dataset.img;
            attachedName.textContent = this.dataset.name;
            attachedPrice.textContent = formatRupiah(this.dataset.price);
            attachedContainer.style.display = 'flex';
            
            productPicker.classList.remove('show');
            setTimeout(() => productPicker.style.display = 'none', 300);
          });
        });
      });
  }

  attachedRemove.addEventListener('click', function() {
    attachedProductId = null;
    attachedContainer.style.display = 'none';
  });

  // Load customer list (admin)
  function loadCustomerList() {
    const body = document.getElementById('chatBody');
    // Ensure picker is kept, just replace inner HTML except picker
    const picker = document.getElementById('chatProductPicker');
    
    fetch(baseUrl + 'chat/getCustomers')
      .then(r => r.json())
      .then(data => {
        let html = '';
        if (!data.customers || data.customers.length === 0) {
          html = '<div class="chat-customer-list" id="chatCustomerList"><div class="chat-empty-state"><i class="bi bi-people"></i><span>Belum ada pelanggan terdaftar.</span></div></div>';
        } else {
          html = '<div class="chat-customer-list" id="chatCustomerList">';
          data.customers.forEach(c => {
            let initial = c.username.charAt(0).toUpperCase();
            let unreadBadge = c.unread > 0 ? `<div class="cust-unread">${c.unread}</div>` : '';
            html += `<button class="chat-customer-item" data-id="${c.id}" data-name="${c.username}">
                       <div class="cust-avatar">${initial}</div>
                       <div class="cust-name">${c.username}</div>
                       ${unreadBadge}
                     </button>`;
          });
          html += '</div>';
        }
        
        body.innerHTML = html;
        body.appendChild(picker);

        // Bind click events
        body.querySelectorAll('.chat-customer-item').forEach(item => {
          item.addEventListener('click', function() {
            targetId = parseInt(this.dataset.id);
            let targetName = this.dataset.name;
            openChatWith(targetId, targetName);
          });
        });
      })
      .catch(() => {
        body.innerHTML = '<div class="chat-customer-list" id="chatCustomerList"><div class="chat-empty-state"><i class="bi bi-exclamation-triangle"></i><span>Gagal memuat data.</span></div></div>';
        body.appendChild(picker);
      });
  }

  // Open chat with specific user (admin)
  function openChatWith(id, name) {
    stopAdminPolling(); // stop listing poll when entering chat
    const body = document.getElementById('chatBody');
    const picker = document.getElementById('chatProductPicker');
    
    body.innerHTML = '<div class="chat-messages" id="chatMessages"><div class="chat-empty-state"><div class="spinner-border spinner-border-sm text-primary"></div><span class="mt-2">Memuat percakapan...</span></div></div>';
    body.appendChild(picker);
    
    backBtn.style.display = 'block';
    headerTitle.textContent = name;
    headerSub.textContent = 'Pelanggan';
    inputWrapper.style.display = 'flex';
    loadMessages();
    startPolling();
  }

  // Load messages
  function loadMessages() {
    if (!targetId) return;
    fetch(baseUrl + 'chat/getMessages/' + targetId)
      .then(r => r.json())
      .then(res => {
        if (res.status !== 'success') return;
        const container = document.getElementById('chatMessages');
        if (!container) return;

        let html = '';
        if (!res.data || res.data.length === 0) {
          html = '<div class="chat-empty-state"><i class="bi bi-chat-left-text"></i><span>Belum ada percakapan.<br>Mulai sapa sekarang!</span></div>';
        } else {
          res.data.forEach(msg => {
            let isMe = false;
            let isCustomer = (msg.sender_id != 1); // Assuming Admin is 1
            
            if (isAdmin) {
              isMe = (msg.sender_id == 1);
            } else {
              isMe = (msg.sender_id == currentUserId);
            }
            
            let alignClass = isMe ? 'align-right' : 'align-left';
            let themeClass = isCustomer ? 'theme-brown' : 'theme-white';
            
            let d = new Date(msg.created_at);
            let time = d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0');
            
            let checkIcon = '';
            if (isMe) {
              if (msg.is_read == 1) {
                checkIcon = '<i class="bi bi-check-all ms-1" style="font-size:14px; color:#93c5fd;"></i>'; // Blueish for read
              } else {
                checkIcon = '<i class="bi bi-check ms-1" style="font-size:14px; opacity:0.8;"></i>'; // Single for sent
              }
            }
            
            let productHtml = '';
            if (msg.product_id && msg.product_name) {
              productHtml = `
                <div class="chat-product-card">
                  <img src="${baseUrl}NiceAdmin/assets/img/${msg.product_foto}" alt="Product">
                  <div class="chat-product-card-info">
                    <div class="chat-product-card-name">${msg.product_name}</div>
                    <div class="chat-product-card-price">${formatRupiah(msg.product_price)}</div>
                  </div>
                </div>
              `;
            }

            html += `<div class="chat-msg-row ${alignClass}">
                       <div class="chat-bubble-widget ${themeClass}">
                         ${productHtml}
                         <div>${escapeHtml(msg.message)}</div>
                         <div class="chat-bubble-time d-flex align-items-center justify-content-${isMe ? 'end' : 'start'}">
                           ${time} ${checkIcon}
                         </div>
                       </div>
                     </div>`;
          });
        }

        let wasAtBottom = (container.scrollHeight - container.scrollTop - container.clientHeight) < 60;
        container.innerHTML = html;
        if (wasAtBottom || res.data.length <= 10) {
          container.scrollTop = container.scrollHeight;
        }
      });
  }

  // Send message
  function sendMessage() {
    let msg = msgInput.value.trim();
    if ((!msg && !attachedProductId) || !targetId) return;

    let formData = new FormData();
    formData.append('receiver_id', targetId);
    formData.append('message', msg);
    if (attachedProductId) {
      formData.append('product_id', attachedProductId);
    }

    // Reset UI optimistically
    msgInput.value = '';
    attachedProductId = null;
    attachedContainer.style.display = 'none';

    fetch(baseUrl + 'chat/sendMessage', { method: 'POST', body: formData })
      .then(r => r.json())
      .then(res => {
        if (res.status === 'success') {
          loadMessages();
        }
      });
  }

  sendBtn.addEventListener('click', sendMessage);
  msgInput.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); sendMessage(); }
  });

  // Bind Quick Replies
  const quickReplies = document.querySelectorAll('.quick-reply-btn');
  quickReplies.forEach(btn => {
    btn.addEventListener('click', function() {
      msgInput.value = this.textContent;
      msgInput.focus();
    });
  });

  // Polling
  let adminPollInterval = null;
  function startPolling() { stopPolling(); pollInterval = setInterval(loadMessages, 3000); }
  function stopPolling() { if (pollInterval) { clearInterval(pollInterval); pollInterval = null; } }
  
  function startAdminPolling() { stopAdminPolling(); adminPollInterval = setInterval(loadCustomerList, 5000); }
  function stopAdminPolling() { if (adminPollInterval) { clearInterval(adminPollInterval); adminPollInterval = null; } }

  // Unread check (for badge dot)
  function checkUnread() {
    fetch(baseUrl + 'chat/getUnread')
      .then(r => r.json())
      .then(res => {
        if (res.count > 0) {
          badgeDot.style.display = 'block';
        } else {
          badgeDot.style.display = 'none';
        }
      })
      .catch(() => {});
  }
  setInterval(checkUnread, 5000);
  checkUnread();

  // Helper
  function escapeHtml(text) {
    if (!text) return '';
    let div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
  }
})();
</script>
