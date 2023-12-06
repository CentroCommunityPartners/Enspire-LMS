<?php
$user_id   = get_current_user_id();
$avatar    = @json_decode( get_user_meta( $user_id, 'avatar', true ) );
$avatar_id = get_eavatar_id( $user_id );
$avatar    = ! $avatar ? '' : $avatar;
?>

<div class="avatar-upload <?= ( $avatar_id ? 'has-avatar' : '' ) ?>">
    <div class="avatar-upload__controls">
        <button type="button" id="avatar-remove" class="button button-secondary">Remove</button>
    </div>

    <div class="avatar-upload__wrap">

        <span class="upload-text">
            <img src="data:image/svg+xml,%3Csvg width='24' height='24' viewBox='0 0 24 24' fill='%23959595' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 15H13V9H16L12 4L8 9H11V15Z' fill='%23959595'/%3E%3Cpath d='M20 18H4V11H2V18C2 19.103 2.897 20 4 20H20C21.103 20 22 19.103 22 18V11H20V18Z' fill='%23959595'/%3E%3C/svg%3E%0A">
            Upload<br>Photo
        </span>
        <input type="file" id="avatar-input" class="avatar-upload__input" name="avatar-upload-image" accept=".jpg,.jpeg,.png,.gif"/>

		<?php if ( $user_id ): ?>
            <input type="hidden" name="avatar-id" value="<?= $avatar_id ?>"/>
		<?php else: ?>
            <input type="hidden" name="avatar-base64" value=""/>
		<?php endif; ?>
        <div class="avatar-upload__preview">
            <img class="avatar-upload__image" src="<?= get_eavatar_image_url( $user_id ) ?>" alt="your image"/>
        </div>
    </div>

    <div class="avatar-upload__messages">

    </div>
</div>

<a data-fancybox data-src="#modal-crop" href="javascript:;" style="display: none">
    Open Cropper Popup
</a>
<div class="modal modal-cropper" id="modal-crop" tabindex="-1" role="dialog" aria-hidden="true" style="display:none;">
    <div class="modal-header">
        <h3 class="modal-title">Crop & Upload Image</h3>
    </div>
    <div class="modal-body">
        <div class="img-container">
            <div class="img-crop-wrapper">
                <img src="" id="sample_image"/>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" id="crop" class="button button-primary">
            <span class="crop-text">Upload</span>
            <i class="avatar-loader"></i>
        </button>
        <button type="button" data-fancybox-close="" title="Cancel" class="button button-secondary">
            Cancel
        </button>
    </div>
</div>