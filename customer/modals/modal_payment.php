<?php
echo '<div class="modal fade" id="payments" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="paymentsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Payment | Cuyapo Food Hub</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <div class="modal-body text-center">
          <p class="text-danger">* Please head to the counter after using online payment for verification *</p>';
if ($wallet_qr != '') {
  echo '<img src="../uploads/' . $wallet_qr . '" style="max-width: 100%; max-height: 300px;">';
} else {
  echo '<p>Online Payment is not available right now.</p>';
}
echo '
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>';
?>
