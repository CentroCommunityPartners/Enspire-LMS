<div class="row">
    <h3 class="col col-sm-12">Billing History</h3>
</div>


<div class="row" id="rcp-payment-history">
    <div class="col col-sm-3"><strong><?php _e( 'Invoice #', 'rcp' ); ?></strong></div>
    <div class="col col-sm-3"><strong><?php _e( 'Subscription', 'rcp' ); ?></strong></div>
    <div class="col col-sm-3"><strong><?php _e( 'Amount', 'rcp' ); ?></strong></div>
    <div class="col col-sm-3"><strong><?php _e( 'Date', 'rcp' ); ?></strong></div>
</div>

<?php if ( rcp_get_user_payments() ) : ?>
    <div class="es-expand">
		<?php foreach ( rcp_get_user_payments() as $payment ) : ?>

            <div class="invoice-row row row--gutter row--offset">
                <div class="col col-sm-3">
                    <a href="<?= rcp_get_pdf_download_url( $payment->id ); ?>">
						<?= $payment->id; ?>
                        <br>
						<?php _e( 'PDF Receipt', 'rcp' ); ?>
                    </a>
                </div>
                <div class="col col-sm-3"><?= $payment->subscription; ?></div>
                <div class="col col-sm-3"><?= rcp_currency_filter( $payment->amount ); ?></div>
                <div class="col col-sm-3">
					<?= date_i18n( get_option( 'date_format' ), strtotime( $payment->date ) ); ?>
                </div>
            </div>

		<?php endforeach; ?>
    </div>
<?php else : ?>
    <p><?php _e( 'You have not made any payments.', 'rcp' ); ?></p>
<?php endif; ?>

