<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="pagetitle">
  <h1>Chat & Dukungan</h1>
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= base_url() ?>">Home</a></li>
      <li class="breadcrumb-item active">Chat</li>
    </ol>
  </nav>
</div>

<section class="section">
  <div class="row">
    <?php if ($role == 'admin' || $role == 'owner'): ?>
      <!-- Admin View: Customer List -->
      <div class="col-lg-4">
        <div class="card">
          <div class="card-body p-3">
            <h5 class="card-title p-0 mb-3" style="font-size: 16px;">Daftar Pelanggan</h5>
            <div class="list-group" id="customer-list" style="max-height: 500px; overflow-y: auto;">
              <?php foreach ($customers as $c): ?>
                <a href="javascript:void(0)" class="list-group-item list-group-item-action chat-user" data-id="<?= $c['id'] ?>">
                  <div class="d-flex w-100 justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                      <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 35px; height: 35px;">
                        <?= strtoupper(substr($c['username'], 0, 1)) ?>
                      </div>
                      <h6 class="mb-0 fw-semibold"><?= esc($c['username']) ?></h6>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
      <!-- Chat Area -->
      <div class="col-lg-8">
        <div class="card chat-card">
          <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
            <h5 class="card-title m-0 p-0" id="chat-title">Pilih pelanggan untuk memulai obrolan</h5>
          </div>
          <div class="card-body chat-body" id="chat-box" style="height: 400px; overflow-y: auto; background-color: #f8f9fa; padding: 20px;">
            <div class="text-center text-muted mt-5">Belum ada obrolan yang dipilih.</div>
          </div>
          <div class="card-footer bg-white border-top p-3 d-none" id="chat-input-area">
            <form id="chat-form" class="d-flex gap-2">
              <input type="hidden" id="receiver_id" name="receiver_id">
              <input type="text" id="chat-message" class="form-control rounded-pill" placeholder="Ketik pesan..." required autocomplete="off">
              <button type="submit" class="btn rounded-pill px-4" style="background: #8B5A2B; color: white;"><i class="bi bi-send-fill"></i></button>
            </form>
          </div>
        </div>
      </div>
    <?php else: ?>
      <!-- Customer View: Chat Area Only -->
      <div class="col-lg-12">
        <div class="card chat-card">
          <div class="card-header bg-white border-bottom py-3 d-flex align-items-center gap-2">
            <div class="bg-success text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 40px; height: 40px;">
              <i class="bi bi-headset"></i>
            </div>
            <div>
              <h5 class="card-title m-0 p-0 fs-6">Admin Sebul Watch Co.</h5>
              <small class="text-success"><i class="bi bi-circle-fill" style="font-size: 8px;"></i> Online</small>
            </div>
          </div>
          <div class="card-body chat-body" id="chat-box" style="height: 400px; overflow-y: auto; background-color: #f8f9fa; padding: 20px;">
            <div class="text-center text-muted mt-5"><i class="spinner-border text-primary spinner-border-sm"></i> Memuat percakapan...</div>
          </div>
          <div class="card-footer bg-white border-top p-3" id="chat-input-area">
            <form id="chat-form" class="d-flex gap-2">
              <input type="hidden" id="receiver_id" name="receiver_id" value="<?= $admin_target ?>">
              <input type="text" id="chat-message" class="form-control rounded-pill" placeholder="Ketik pesan untuk Admin..." required autocomplete="off">
              <button type="submit" class="btn rounded-pill px-4" style="background: #8B5A2B; color: white;"><i class="bi bi-send-fill"></i></button>
            </form>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
</section>

<style>
  .chat-bubble {
    max-width: 75%;
    padding: 10px 15px;
    border-radius: 15px;
    margin-bottom: 10px;
    font-size: 14px;
    word-wrap: break-word;
  }
  .chat-left {
    background-color: #fff;
    color: #333;
    border-radius: 15px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
  }
  .chat-right {
    background-color: #8B5A2B;
    background: linear-gradient(135deg, #8B5A2B 0%, #5C4033 100%);
    color: #fff;
    border-radius: 15px;
    box-shadow: 0 1px 2px rgba(139,90,43,0.3);
  }
  .chat-row {
    display: flex;
    flex-direction: column;
    margin-bottom: 5px;
  }
  .chat-time {
    font-size: 10px;
    color: #999;
    margin-top: 2px;
  }
  .chat-right .chat-time {
    color: #rgba(255,255,255,0.7);
    text-align: right;
  }
</style>

<script>
  let currentTarget = <?= isset($admin_target) ? $admin_target : 'null' ?>;
  const currentUserId = <?= $current_user_id ?>;
  
  function loadMessages() {
    if(!currentTarget) return;
    
    $.ajax({
      url: '<?= base_url("chat/getMessages") ?>/' + currentTarget,
      type: 'GET',
      success: function(res) {
        if(res.status === 'success') {
          let html = '';
          if(res.data.length === 0) {
            html = '<div class="text-center text-muted mt-5">Belum ada percakapan. Mulai sapa sekarang!</div>';
          } else {
            res.data.forEach(msg => {
              let isMe = msg.sender_id == currentUserId;
              let isCustomer = msg.sender_id != 1; // Admin is 1
              let bubbleClass = isCustomer ? 'chat-right' : 'chat-left'; // Reusing chat-right (brown) and chat-left (white) styles
              let alignClass = isMe ? 'align-items-end' : 'align-items-start';
              
              let d = new Date(msg.created_at);
              let timeStr = d.getHours().toString().padStart(2, '0') + ':' + d.getMinutes().toString().padStart(2, '0');
              
              html += `<div class="chat-row ${alignClass}">
                         <div class="chat-bubble ${bubbleClass}">
                           ${msg.message}
                           <div class="chat-time" style="color: ${isCustomer ? 'rgba(255,255,255,0.7)' : '#999'}; text-align: ${isMe ? 'right' : 'left'};">${timeStr}</div>
                         </div>
                       </div>`;
            });
          }
          let box = $('#chat-box');
          let atBottom = (box[0].scrollHeight - box.scrollTop() - box.outerHeight()) < 50;
          box.html(html);
          if (atBottom || html.includes('Belum ada percakapan')) {
              box.scrollTop(box[0].scrollHeight);
          }
        }
      }
    });
  }

  $(document).ready(function() {
    <?php if($role == 'pelanggan'): ?>
      loadMessages();
      setInterval(loadMessages, 3000);
    <?php endif; ?>

    $('.chat-user').click(function() {
      $('.chat-user').removeClass('active bg-light');
      $(this).addClass('active bg-light');
      currentTarget = $(this).data('id');
      let name = $(this).find('h6').text();
      $('#chat-title').html('<div class="d-flex align-items-center gap-2"><div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center fw-bold" style="width: 35px; height: 35px;">' + name.charAt(0).toUpperCase() + '</div>' + name + '</div>');
      $('#receiver_id').val(currentTarget);
      $('#chat-input-area').removeClass('d-none');
      $('#chat-box').html('<div class="text-center text-muted mt-5"><i class="spinner-border text-primary spinner-border-sm"></i> Memuat percakapan...</div>');
      loadMessages();
    });

    <?php if($role == 'admin' || $role == 'owner'): ?>
      setInterval(function() {
        if(currentTarget) loadMessages();
      }, 3000);
    <?php endif; ?>

    $('#chat-form').submit(function(e) {
      e.preventDefault();
      let msg = $('#chat-message').val();
      if(!msg.trim() || !currentTarget) return;
      
      $.ajax({
        url: '<?= base_url("chat/sendMessage") ?>',
        type: 'POST',
        data: {
          receiver_id: currentTarget,
          message: msg
        },
        success: function(res) {
          if(res.status === 'success') {
            $('#chat-message').val('');
            loadMessages();
            setTimeout(function() {
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            }, 100);
          }
        }
      });
    });
  });
</script>
<?= $this->endSection() ?>
